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
use Module\Foundation\Events\TrainingMemberUpdated;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Module\Foundation\Http\Resources\MemberResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoundationMember extends Model
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
    protected $table = 'foundation_members';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['foundation-member'];

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
    protected $defaultOrder = 'name';

    /**
     * mapCombos function
     *
     * @param Request $request
     * @return array
     */
    public static function mapCombos(Request $request): array
    {
        return [
            'genders' => FoundationGender::forCombo(),
            'positions' => FoundationPosition::where('scope', 'MEMBER')->forCombo()
        ];
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return array
     */
    public static function mapHeaders(Request $request): array
    {
        return [
            ['title' => 'Name', 'value' => 'name'],
            ['title' => 'Jabatan', 'value' => 'position'],
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
            'position' => $model->position->name,

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
        return [
            'name' => $model->name,
            'slug' => $model->slug,
            'phone' => $model->phone,
            'gender_id' => $model->gender_id,
            'position_id' => $model->position_id,
            'village_id' => $model->village_id,
            'subdistrict_id' => $model->subdistrict_id,
            'regency_id' => $model->regency_id,
            'community_id' => $model->community_id,
            'communitymap_id' => $model->communitymap_id,
            'citizen' => $model->citizen,
            'neighborhood' => $model->neighborhood,
        ];
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
     * position function
     *
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(FoundationPosition::class, 'position_id');
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
            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->phone = $request->phone;
            $model->gender_id = $request->gender_id;
            $model->position_id = $request->position_id;
            $model->village_id = $parent->village_id;
            $model->subdistrict_id = $parent->subdistrict_id;
            $model->regency_id = $parent->regency_id;
            $model->community_id = $parent->id;
            $model->communitymap_id = $parent->communitymap_id;
            $model->citizen = $request->citizen;
            $model->neighborhood = $request->neighborhood;

            $parent->members()->save($model);

            TrainingMemberUpdated::dispatch($model);

            DB::connection($model->connection)->commit();

            return new MemberResource($model);
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
    public static function updateRecord(Request $request, $model, $parent)
    {
        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $request->name;
            $model->slug = $request->slug;
            $model->phone = $request->phone;
            $model->gender_id = $request->gender_id;
            $model->position_id = $request->position_id;
            $model->village_id = $parent->village_id;
            $model->subdistrict_id = $parent->subdistrict_id;
            $model->regency_id = $parent->regency_id;
            $model->community_id = $parent->id;
            $model->communitymap_id = $parent->communitymap_id;
            $model->citizen = $request->citizen;
            $model->neighborhood = $request->neighborhood;
            $model->save();

            TrainingMemberUpdated::dispatch($model);

            DB::connection($model->connection)->commit();

            return new MemberResource($model);
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

            return new MemberResource($model);
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

            return new MemberResource($model);
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

            return new MemberResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
