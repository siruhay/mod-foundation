<?php

namespace Module\Foundation\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Module\Foundation\Events\TrainingOfficialUpdated;
use Module\Foundation\Http\Resources\OfficialResource;

class FoundationOfficial extends FoundationBiodata
{
    /**
     * addGlobalScope function
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('official', function (Builder $builder) {
            $builder->where('type', 'DESA');
        });
    }

    /**
     * mapCombos function
     *
     * @param Request $request
     * @return array
     */
    public static function mapCombos(Request $request, $model = null): array
    {
        return [
            'genders' => FoundationGender::forCombo(),
            'positions' => FoundationPosition::where('scope', 'OFFICIAL')->forCombo(),
            'subdistricts' => FoundationSubdistrict::where('regency_id', 3)->forCombo(),
            'villages' => optional($model)->subdistrict_id ? FoundationVillage::where('district_id', $model->subdistrict_id)->forCombo() : [],
        ];
    }

    /**
     * mapResourceShow function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResourceShow(Request $request, $model): array
    {
        return [
            'name' => $model->name,
            'slug' => $model->slug,
            'phone' => $model->phone,
            'gender_id' => $model->gender_id,
            'position_id' => $model->position_id,
            'village_id' => $model->village_id,
            'subdistrict_id' => $model->subdistrict_id,
            'regency_id' => $model->regency_id,
        ];
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request, $parent = null)
    {
        $model          = new static();
        $village        = FoundationVillage::find($request->village_id);

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->phone = $request->phone;
            $model->gender_id = $request->gender_id;
            $model->position_id = $request->position_id;
            $model->regency_id = $village->regency_id;
            $model->subdistrict_id = $village->district_id;
            $model->village_id = $request->village_id;

            $model->save();

            if ($model->slug) {
                TrainingOfficialUpdated::dispatch($model, ['mytraining-member']);
            }

            DB::connection($model->connection)->commit();

            return new OfficialResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model update method
     *
     * @param Request $request
     * @param [type] $model
     * @return void
     */
    public static function updateRecord(Request $request, $model, $parent = null)
    {
        $village        = FoundationVillage::find($request->village_id);

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->phone = $request->phone;
            $model->gender_id = $request->gender_id;
            $model->position_id = $request->position_id;
            $model->regency_id = $village->regency_id;
            $model->subdistrict_id = $village->district_id;
            $model->village_id = $request->village_id;
            $model->save();

            if ($model->slug) {
                TrainingOfficialUpdated::dispatch($model, ['mytraining-member']);
            }

            DB::connection($model->connection)->commit();

            return new OfficialResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model delete method
     *
     * @param [type] $model
     * @return void
     */
    public static function deleteRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->delete();

            DB::connection($model->connection)->commit();

            return new OfficialResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model restore method
     *
     * @param [type] $model
     * @return void
     */
    public static function restoreRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->restore();

            DB::connection($model->connection)->commit();

            return new OfficialResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * The model destroy method
     *
     * @param [type] $model
     * @return void
     */
    public static function destroyRecord($model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->forceDelete();

            DB::connection($model->connection)->commit();

            return new OfficialResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
