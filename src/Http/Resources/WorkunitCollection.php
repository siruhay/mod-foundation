<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationWorkunit;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkunitCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return WorkunitResource::collection($this->collection);
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
                'combos' => FoundationWorkunit::mapCombos($request),

                /** the page data filter */
                'filters' => FoundationWorkunit::mapFilters(),

                /** the table header */
                'headers' => FoundationWorkunit::mapHeaders($request),

                /** the page icon */
                'icon' => FoundationWorkunit::getPageIcon('foundation-workunit'),

                /** the record key */
                'key' => FoundationWorkunit::getDataKey(),

                /** the page default */
                'recordBase' => FoundationWorkunit::mapRecordBase($request),

                /** the page statuses */
                'statuses' => FoundationWorkunit::mapStatuses($request),

                /** the page data mode */
                'trashed' => $request->trashed ?: false,

                /** the page title */
                'title' => FoundationWorkunit::getPageTitle($request, 'foundation-workunit'),

                /** the usetrash flag */
                'usetrash' => FoundationWorkunit::hasSoftDeleted(),
            ]
        ];
    }
}
