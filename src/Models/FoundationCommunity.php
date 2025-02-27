<?php

namespace Module\Foundation\Models;

use Illuminate\Http\Request;
use Module\System\Traits\HasMeta;
use Illuminate\Support\Facades\DB;
use Module\System\Traits\Filterable;
use Module\System\Traits\Searchable;
use Module\System\Traits\HasPageSetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Module\Foundation\Http\Resources\CommunityResource;

class FoundationCommunity extends Model
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
    protected $table = 'foundation_communities';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['foundation-community'];

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
     * toFilterableArray function
     * 
     * {param} => {field} | {mode}::{field} 
     * mode = raw | json | month | eager | orwhere
     *
     * @return array
     */
    protected function toFilterableArray(): array
    {
        return [
            'subdistrict' => 'subdistrict_id',
            'communitymap' => 'communitymap_id'
        ];
    }

    /**
     * mapFilters function
     * 
     * type: Combobox, DateInput, NumberInput, Select, Textfield, TimeInput, Hidden
     *
     * @return array
     */
    public static function mapFilters(): array
    {
        return [
            'communitymap' => [
                'title' => 'Tipe',
                'data' => FoundationCommunitymap::forCombo(),
                'used' => false,
                'operators' => ['=', '<', '>'],
                'operator' => ['='],
                'type' => 'Select',
                'value' => null,
            ],

            'subdistrict' => [
                'title' => 'Kecamatan',
                'data' => FoundationSubdistrict::where('regency_id', 3)->forCombo(),
                'used' => false,
                'operators' => ['=', '<', '>'],
                'operator' => ['='],
                'type' => 'Select',
                'value' => null,
            ],
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
        return [
            'communitymaps' => FoundationCommunitymap::forCombo(),
            'subdistricts' => FoundationSubdistrict::where('regency_id', 3)->forCombo(),
            'villages' => optional($model)->subdistrict_id ? FoundationVillage::where('district_id', $model->subdistrict_id)->forCombo() : [],
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
            ['title' => 'Kecamatan', 'value' => 'subdistrict'],
            ['title' => 'Desa/Kel', 'value' => 'village'],
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

            'subdistrict' => optional($model->subdistrict)->name,
            'subdistrict_id' => $model->subdistrict_id,

            'village' => optional($model->village)->name,
            'village_id' => $model->village_id,

            'citizen' => $model->citizen,
            'neighborhood' => $model->neighborhood,

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
            'id'                => $model->id,
            'name'              => $model->name,
            'communitymap_id'   => $model->communitymap_id,
            'subdistrict_id'    => $model->subdistrict_id,
            'village_id'        => $model->village_id,
            'citizen'           => $model->citizen,
            'neighborhood'      => $model->neighborhood,
        ];
    }

    /**
     * Undocumented function
     *
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(FoundationMember::class, 'community_id');
    }

    /**
     * subdistrict function
     *
     * @return HasMany
     */
    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(FoundationSubdistrict::class, 'subdistrict_id');
    }

    /**
     * village function
     *
     * @return HasMany
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(FoundationVillage::class, 'village_id');
    }

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request)
    {
        $communitymap   = FoundationCommunitymap::find($request->communitymap_id);
        $subdistrict    = FoundationSubdistrict::find($request->subdistrict_id);
        $village        = FoundationVillage::find($request->village_id);
        $name           = optional($communitymap)->short ? optional($communitymap)->short . ' DESA ' . optional($village)->name : optional($communitymap)->name;
        $slug           = sha1($name);

        if ($model = static::firstWhere('slug', $slug)) {
            return response()->json([
                'success' => false,
                'message' => "Lembaga: $name sudah ada dalam daftar."
            ], 500);
        }

        $model          = new static();

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $name;
            $model->slug = $slug;
            $model->communitymap_id = $request->communitymap_id;
            $model->subdistrict_id = $request->subdistrict_id;
            $model->village_id = $request->village_id;
            $model->regency_id = optional($subdistrict)->regency_id;
            $model->save();

            DB::connection($model->connection)->commit();

            return new CommunityResource($model);
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
        $communitymap   = FoundationCommunitymap::find($request->communitymap_id);
        $subdistrict    = FoundationSubdistrict::find($request->subdistrict_id);
        $village        = FoundationVillage::find($request->village_id);
        $name           = optional($communitymap)->short ? optional($communitymap)->short . ' DESA ' . optional($village)->name : optional($communitymap)->name;
        $slug           = sha1($name);
        $exists         = static::firstWhere('slug', $slug);

        if ($exists && $exists->id !== $model->id) {
            return response()->json([
                'success' => false,
                'message' => "Lembaga: $name sudah ada dalam daftar."
            ], 500);
        }

        DB::connection($model->connection)->beginTransaction();

        try {
            $model->name = $name;
            $model->slug = $slug;
            $model->communitymap_id = $request->communitymap_id;
            $model->subdistrict_id = $request->subdistrict_id;
            $model->village_id = $request->village_id;
            $model->regency_id = optional($subdistrict)->regency_id;
            $model->save();

            DB::connection($model->connection)->commit();

            return new CommunityResource($model);
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

            return new CommunityResource($model);
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

            return new CommunityResource($model);
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

            return new CommunityResource($model);
        } catch (\Exception $e) {
            DB::connection($model->connection)->rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
