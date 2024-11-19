<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationMember;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return MemberResource::collection($this->collection);
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
                'combos' => FoundationMember::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationMember::mapFilters(),

                /** the table header */
                'headers' => FoundationMember::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationMember::getPageIcon('foundation-member'),

                /** the record key */
                'key' => FoundationMember::getDataKey(),

                /** the page default */
                'recordBase' => FoundationMember::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationMember::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationMember::getPageTitle($request, 'foundation-member'),

                /** the usetrash flag */
                'usetrash' => FoundationMember::hasSoftDeleted(),
            ]
        ];
    }
}
