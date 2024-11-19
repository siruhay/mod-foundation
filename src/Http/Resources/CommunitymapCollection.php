<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationCommunitymap;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunitymapCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return CommunitymapResource::collection($this->collection);
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
                'combos' => FoundationCommunitymap::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationCommunitymap::mapFilters(),

                /** the table header */
                'headers' => FoundationCommunitymap::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationCommunitymap::getPageIcon('foundation-communitymap'),

                /** the record key */
                'key' => FoundationCommunitymap::getDataKey(),

                /** the page default */
                'recordBase' => FoundationCommunitymap::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationCommunitymap::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationCommunitymap::getPageTitle($request, 'foundation-communitymap'),

                /** the usetrash flag */
                'usetrash' => FoundationCommunitymap::hasSoftDeleted(),
            ]
        ];
    }
}
