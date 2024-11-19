<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationSubdistrict;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubdistrictCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SubdistrictResource::collection($this->collection);
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
                'combos' => FoundationSubdistrict::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationSubdistrict::mapFilters(),

                /** the table header */
                'headers' => FoundationSubdistrict::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationSubdistrict::getPageIcon('foundation-subdistrict'),

                /** the record key */
                'key' => FoundationSubdistrict::getDataKey(),

                /** the page default */
                'recordBase' => FoundationSubdistrict::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationSubdistrict::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationSubdistrict::getPageTitle($request, 'foundation-subdistrict'),

                /** the usetrash flag */
                'usetrash' => FoundationSubdistrict::hasSoftDeleted(),
            ]
        ];
    }
}
