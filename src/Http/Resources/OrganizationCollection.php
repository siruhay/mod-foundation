<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationOrganization;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return OrganizationResource::collection($this->collection);
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
                'combos' => FoundationOrganization::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationOrganization::mapFilters(),

                /** the table header */
                'headers' => FoundationOrganization::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationOrganization::getPageIcon('foundation-organization'),

                /** the record key */
                'key' => FoundationOrganization::getDataKey(),

                /** the page default */
                'recordBase' => FoundationOrganization::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationOrganization::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationOrganization::getPageTitle($request, 'foundation-organization'),

                /** the usetrash flag */
                'usetrash' => FoundationOrganization::hasSoftDeleted(),
            ]
        ];
    }
}
