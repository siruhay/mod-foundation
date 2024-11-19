<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationOfficial;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OfficialCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return OfficialResource::collection($this->collection);
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
                'combos' => FoundationOfficial::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationOfficial::mapFilters(),

                /** the table header */
                'headers' => FoundationOfficial::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationOfficial::getPageIcon('foundation-official'),

                /** the record key */
                'key' => FoundationOfficial::getDataKey(),

                /** the page default */
                'recordBase' => FoundationOfficial::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationOfficial::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationOfficial::getPageTitle($request, 'foundation-official'),

                /** the usetrash flag */
                'usetrash' => FoundationOfficial::hasSoftDeleted(),
            ]
        ];
    }
}
