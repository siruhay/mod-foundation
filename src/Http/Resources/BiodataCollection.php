<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationBiodata;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BiodataCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return BiodataResource::collection($this->collection);
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
                'combos' => FoundationBiodata::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationBiodata::mapFilters(),

                /** the table header */
                'headers' => FoundationBiodata::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationBiodata::getPageIcon('foundation-biodata'),

                /** the record key */
                'key' => FoundationBiodata::getDataKey(),

                /** the page default */
                'recordBase' => FoundationBiodata::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationBiodata::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationBiodata::getPageTitle($request, 'foundation-biodata'),

                /** the usetrash flag */
                'usetrash' => FoundationBiodata::hasSoftDeleted(),
            ]
        ];
    }
}
