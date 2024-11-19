<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationVillage;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VillageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return VillageResource::collection($this->collection);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with($request): array
    {
        if ($request->has('initialized')) {
            return [];
        }

        return [
            'setups' => [
                /** the page combo */
                'combos' => FoundationVillage::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationVillage::mapFilters(),

                /** the table header */
                'headers' => FoundationVillage::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationVillage::getPageIcon('foundation-village'),

                /** the record key */
                'key' => FoundationVillage::getDataKey(),

                /** the page default */
                'recordBase' => FoundationVillage::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationVillage::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationVillage::getPageTitle($request, 'foundation-village'),

                /** the usetrash flag */
                'usetrash' => FoundationVillage::hasSoftDeleted(),
            ]
        ];
    }
}
