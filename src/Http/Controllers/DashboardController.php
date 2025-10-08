<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Module\Reference\Models\ReferenceVillage;
use Module\Foundation\Models\FoundationOfficial;
use Module\Foundation\Models\FoundationCommunity;
use Module\Reference\Models\ReferenceSubdistrict;
use Module\Foundation\Models\FoundationSubdistrict;

class DashboardController extends Controller
{
    /**
     * index function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request): void
    {
        //
    }

    /**
     * upload function
     *
     * @param Request $request
     * @return void
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => "required|file|max:2048"
        ]);

        if ($request->hasFile('file') && $request->file('file')) {
            $fileslug = $request->slug;
            $filename = $request->uuid . $request->extension;
            $filepath = $fileslug . DIRECTORY_SEPARATOR . $filename;

            if (Storage::disk('uploads')->putFileAs($fileslug, $request->file('file'), $filename)) {
                return response()->json([
                    'path' => $filepath
                ], 200);
            }
        }

        return response()->json([
            'status' => 422,
            'message' => 'Upload file bermasalah'
        ], 422);
    }

    /**
     * download function
     *
     * @param Request $request
     * @return void
     */
    public function download(Request $request)
    {
        if (!Storage::disk('uploads')->exists($request->path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        return optional(Storage::disk('uploads'))->download($request->path, 'sk-ppk.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="sample.pdf"',
        ]);
    }

    /**
     * destroy function
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        if (!Storage::disk('uploads')->exists($request->path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        if (Storage::disk('uploads')->delete($request->path)) {
            return response()->json([
                'success' => true,
                'message' => 'Hapus file dari server berhasil.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hapus file dari server gagal.'
        ], 500);
    }

    /**
     * combos function
     *
     * @param Request $request
     * @return mixed
     */
    public function combos(Request $request): mixed
    {
        if ($request->has('regency')) {
            return ReferenceSubdistrict::where('regency_id', $request->regency)->forCombo();
        }

        if ($request->has('subdistric')) {
            return ReferenceVillage::where('district_id', $request->subdistric)->forCombo();
        }

        return [];
    }

    /**
     * mapReportData function
     *
     * @param [type] $type
     * @return array
     */
    public static function mapReportData(Request $request): array
    {
        switch ($request->type) {
            case 'official':
                if ($request->subdistrict) {
                    $records = FoundationOfficial::with(['position', 'village'])->where('subdistrict_id', $request->subdistrict)->get();
                } else {
                    $records = FoundationOfficial::with(['position', 'village'])->get();
                }

                return [
                    'title'         => 'DAFTAR PEGAWAI',
                    'periode'       => now()->translatedFormat('d F Y'),
                    'subdistrict'   => optional(FoundationSubdistrict::find($request->subdistrict))->name,
                    'records'       => $records
                ];

            case 'community':
                if ($request->subdistrict) {
                    $records = FoundationCommunity::with(['communitymap', 'official', 'village'])->where('subdistrict_id', $request->subdistrict)->get();
                } else {
                    $records = FoundationCommunity::with(['communitymap', 'official', 'village'])->get();
                }

                return [
                    'title'         => 'DAFTAR LEMBAGA',
                    'periode'       => now()->translatedFormat('d F Y'),
                    'subdistrict'   => optional(FoundationSubdistrict::find($request->subdistrict))->name,
                    'records'       => $records
                ];

            default:
                return [];
        }
    }

    /**
     * report function
     *
     * @param Request $request
     * @return Illuminate\Contracts\View\View
     */
    public function report(Request $request): View | JsonResponse
    {
        if (!$request->has('type')) {
            return response()->json([
                'record' => [
                    'type' => null,
                    'subdistrict' => null
                ],

                'setups' => [
                    'combos' => [
                        'types' => [
                            ['title' => 'Daftar Pegawai', 'value' => 'official'],
                            ['title' => 'Daftar Lembaga', 'value' => 'community'],
                        ],

                        'months' => [
                            ['title' => 'Januari', 'value' => 1],
                            ['title' => 'Februari', 'value' => 2],
                            ['title' => 'Maret', 'value' => 3],
                            ['title' => 'April', 'value' => 4],
                            ['title' => 'Mei', 'value' => 5],
                            ['title' => 'Juni', 'value' => 6],
                            ['title' => 'Juli', 'value' => 7],
                            ['title' => 'Agustus', 'value' => 8],
                            ['title' => 'September', 'value' => 9],
                            ['title' => 'Oktober', 'value' => 10],
                            ['title' => 'Nopember', 'value' => 11],
                            ['title' => 'Desember', 'value' => 12],
                        ],

                        'subdistricts' => FoundationSubdistrict::forCombo()
                    ]
                ]
            ]);
        }

        $reportData = static::mapReportData($request);

        return view('foundation::reports.' . $request->type, $reportData);
    }
}
