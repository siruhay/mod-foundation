<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Models\FoundationOfficial;
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
     * mapReportData function
     *
     * @param [type] $type
     * @return array
     */
    static function mapReportData(Request $request): array
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
