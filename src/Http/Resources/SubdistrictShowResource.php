<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationSubdistrict;
use Module\System\Http\Resources\UserLogActivity;

class SubdistrictShowResource extends JsonResource
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
            'record' => FoundationSubdistrict::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationSubdistrict::mapCombos($request, $this),

                'icon' => FoundationSubdistrict::getPageIcon('foundation-subdistrict'),

                'key' => FoundationSubdistrict::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationSubdistrict::mapStatuses($request, $this),

                'title' => FoundationSubdistrict::getPageTitle($request, 'foundation-subdistrict'),
            ],
        ];
    }
}
