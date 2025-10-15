<?php

namespace Module\Foundation\Models;

use Illuminate\Http\Request;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Models\SystemUser;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Foundation\Http\Resources\BiodataResource;

class FoundationBiodata extends Model
{
    use Filterable;
    use HasMeta;
    use HasPageSetup;
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
    protected $table = 'foundation_biodatas';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['foundation-biodata'];

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
    protected $defaultOrder = 'position_id';

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
            ['title' => 'NIK/NIP', 'value' => 'slug'],
            ['title' => 'Position', 'value' => 'position_name'],
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
            'slug' => $model->slug,
            'position_name' => optional($model->position)->name,

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
     * gender function
     *
     * @return BelongsTo
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(FoundationGender::class, 'gender_id');
    }

    /**
     * faith function
     *
     * @return BelongsTo
     */
    public function faith(): BelongsTo
    {
        return $this->belongsTo(FoundationFaith::class, 'faith_id');
    }

    /**
     * position function
     *
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(FoundationPosition::class, 'position_id');
    }

    /**
     * regency function
     *
     * @return BelongsTo
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(FoundationRegency::class, 'regency_id');
    }

    /**
     * subdistrict function
     *
     * @return BelongsTo
     */
    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(FoundationSubdistrict::class, 'subdistrict_id');
    }

    /**
     * user function
     *
     * @return MorphOne
     */
    public function user(): MorphOne
    {
        return $this->morphOne(SystemUser::class, 'userable');
    }

    /**
     * village function
     *
     * @return BelongsTo
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(FoundationVillage::class, 'village_id');
    }

    /**
     * workunitable function
     *
     * @return MorphTo
     */
    public function workunitable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request, $parent)
    {
        $model = new static();

        DB::connection($model->connection)->beginTransaction();

        try {
            // ...
            $model->save();

            DB::connection($model->connection)->commit();

            return new BiodataResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * storeFrom function
     *
     * @param Object $source
     * @return Model|null
     */
    public static function storeFrom(Object $source): Model|null
    {
        if ($model = static::firstWhere('slug', $source->slug)) {
            return $model;
        }

        $model = new static();

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $source->name;
            $model->slug = $source->slug;
            $model->phone = $source->phone;
            $model->kind = $source->kind;
            $model->type = $source->type;
            $model->role = $source->role;
            $model->gender_id = $source->gender_id;
            $model->workunitable_type = $source->workunitable_type;
            $model->workunitable_id = $source->workunitable_id;
            $model->village_id = $source->village_id;
            $model->subdistrict_id = $source->subdistrict_id;
            $model->regency_id = $source->regency_id;
            $model->citizen = $source->citizen;
            $model->neighborhood = $source->neighborhood;
            $model->save();

            DB::connection($model->connection)->commit();

            return $model;
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return null;
        }
    }

    /**
     * The model update method
     *
     * @param Request $request
     * @param [type] $model
     * @return void
     */
    public static function updateRecord(Request $request, $model, $parent)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            // ...
            $model->save();

            DB::connection($model->connection)->commit();

            return new BiodataResource($model);
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

            return new BiodataResource($model);
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

            return new BiodataResource($model);
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

            return new BiodataResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
