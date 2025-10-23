<?php

namespace Module\Foundation\Models;

use Illuminate\Http\Request;
use Kalnoy\Nestedset\NodeTrait;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Foundation\Http\Resources\PositionResource;

class FoundationPosition extends Model
{
    use Filterable;
    use HasMeta;
    use HasPageSetup;
    use NodeTrait;
    use Searchable;
    use SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'platform';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foundation_positions';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['foundation-position'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array'
    ];

    /**
     * The default key for the order.
     *
     * @var string
     */
    protected $defaultOrder = '_lft';

    /**
     * mapRecordBase function
     *
     * @param Request $request
     * @return array
     */
    public static function mapRecordBase(Request $request): array
    {
        $worktable_type = $request->segment(3) === 'community' ? 'Module\Foundation\Models\FoundationCommunity' : 'Module\Foundation\Models\FoundationWorkunit';
        $worktable_id = $request->segment(4);

        return [
            'id' => null,
            'name' => null,
            'slug' => null,
            'posmap_id' => null,
            'village_id' => null,
            'workunitable_type' => $worktable_type,
            'workunitable_id' => $worktable_id,
            'organization_id' => null,
            'officer_id' => null,
            'position_type' => null,
        ];
    }

    /**
     * mapCombos function
     *
     * @param Request $request
     * @return array
     */
    public static function mapCombos(Request $request, $model = null): array
    {
        $scope = $request->segment(3) === 'community' ? 'LKD' : 'OPD';
        $worktable_type = $request->segment(3) === 'community' ? 'Module\Foundation\Models\FoundationCommunity' : 'Module\Foundation\Models\FoundationWorkunit';
        $worktable_id = $request->segment(4);

        return [
            'organizations' => FoundationOrganization::where('scope', $scope)->orderBy('_lft')->forCombo(),
            'posmaps' => FoundationPosmap::where('scope', $scope)->forCombo(),
            'parents' => FoundationPosition::where('workunitable_type', $worktable_type)->where('workunitable_id', $worktable_id)->forCombo()
        ];
    }

    /**
     * mapHeaders function
     *
     * readonly value?: SelectItemKey<any>
     * readonly title?: string | undefined
     * readonly align?: 'start' | 'end' | 'center' | undefined
     * readonly width?: string | number | undefined
     * readonly minWidth?: string | undefined
     * readonly maxWidth?: string | undefined
     * readonly nowrap?: boolean | undefined
     * readonly sortable?: boolean | undefined
     *
     * @param Request $request
     * @return array
     */
    public static function mapHeaders(Request $request): array
    {
        return [
            ['title' => 'Name', 'value' => 'name'],
            ['title' => 'Officer', 'value' => 'officer_name'],
            ['title' => 'Updated', 'value' => 'updated_at', 'sortable' => false, 'width' => '170'],
        ];
    }

    /**
     * mapResource function
     *
     * @param Request $request
     * @return array
     */
    public static function mapResource(Request $request, $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'scope' => $model->scope,
            'officer_name' => optional($model->officer)->name,
            'nest_deep' => optional($model)->depth,
            'nest_leaf' => optional($model)->isLeaf(),
            'nest_next' => optional($model)->nextSiblings()->count() > 0,
            'nest_prev' => optional($model)->prevSiblings()->count() > 0,
            'subtitle' => (string) $model->updated_at,
            'updated_at' => (string) $model->updated_at,
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
        return static::mapResource($request, $model);
    }

    /**
     * officer function
     *
     * @return BelongsTo
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(FoundationBiodata::class, 'officer_id');
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request, $parent = null)
    {
        $model      = new static();
        $scope      = $parent->scope ?: 'OPD';
        $posmap     = FoundationPosmap::storeFrom($request->posmap, $scope);
        $typeName   = get_class($parent);
        $typeId     = optional($parent)->id;
        $parentId   = $request->parent_id;

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $posmap->name;
            $model->slug = sha1(
                str($posmap->name . ' ' . $typeName . ' ' . $typeId . ' ' . $parentId)->slug()->toString()
            );
            $model->posmap_id = $posmap->id;
            $model->village_id = null;
            $model->workunitable_type = $typeName;
            $model->workunitable_id = $typeId;
            $model->organization_id = null;
            $model->position_type = $request->position_type;
            $model->parent_id = $request->parent_id;
            $model->save();

            DB::connection($model->connection)->commit();

            return new PositionResource($model);
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
    public static function updateRecord(Request $request, $model)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $request->name;
            $model->slug = sha1($request->name);
            $model->scope = $request->scope;
            $model->save();

            DB::connection($model->connection)->commit();

            return new PositionResource($model);
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

            return new PositionResource($model);
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

            return new PositionResource($model);
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

            return new PositionResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
