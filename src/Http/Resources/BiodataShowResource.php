<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationBiodata;
use Module\System\Http\Resources\UserLogActivity;

class BiodataShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            /**
             * the record data
             */
            'record' => FoundationBiodata::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationBiodata::mapCombos($request, $this),

                'icon' => FoundationBiodata::getPageIcon('foundation-biodata'),

                'key' => FoundationBiodata::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationBiodata::mapStatuses($request, $this),

                'title' => FoundationBiodata::getPageTitle($request, 'foundation-biodata'),
            ],
        ];
    }
}
