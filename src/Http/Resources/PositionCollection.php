<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationPosition;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PositionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return PositionResource::collection($this->collection);
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
                'combos' => FoundationPosition::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationPosition::mapFilters(),

                /** the table header */
                'headers' => FoundationPosition::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationPosition::getPageIcon('foundation-position'),

                /** the record key */
                'key' => FoundationPosition::getDataKey(),

                /** the page default */
                'recordBase' => FoundationPosition::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationPosition::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationPosition::getPageTitle($request, 'foundation-position'),

                /** the usetrash flag */
                'usetrash' => FoundationPosition::hasSoftDeleted(),
            ]
        ];
    }
}
