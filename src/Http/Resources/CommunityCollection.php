<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationCommunity;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return CommunityResource::collection($this->collection);
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
                'combos' => FoundationCommunity::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationCommunity::mapFilters(),

                /** the table header */
                'headers' => FoundationCommunity::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationCommunity::getPageIcon('foundation-community'),

                /** the record key */
                'key' => FoundationCommunity::getDataKey(),

                /** the page default */
                'recordBase' => FoundationCommunity::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationCommunity::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationCommunity::getPageTitle($request, 'foundation-community'),

                /** the usetrash flag */
                'usetrash' => FoundationCommunity::hasSoftDeleted(),
            ]
        ];
    }
}
