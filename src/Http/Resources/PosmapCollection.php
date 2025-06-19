<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationPosmap;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PosmapCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PosmapResource::collection($this->collection);
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
                'combos' => FoundationPosmap::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationPosmap::mapFilters(),

                /** the table header */
                'headers' => FoundationPosmap::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationPosmap::getPageIcon('foundation-posmap'),

                /** the record key */
                'key' => FoundationPosmap::getDataKey(),

                /** the page default */
                'recordBase' => FoundationPosmap::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationPosmap::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationPosmap::getPageTitle($request, 'foundation-posmap'),

                /** the usetrash flag */
                'usetrash' => FoundationPosmap::hasSoftDeleted(),
            ]
        ];
    }
}
