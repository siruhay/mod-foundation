<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationPosmap;
use Module\System\Http\Resources\UserLogActivity;

class PosmapShowResource extends JsonResource
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
            'record' => FoundationPosmap::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationPosmap::mapCombos($request, $this),

                'icon' => FoundationPosmap::getPageIcon('foundation-posmap'),

                'key' => FoundationPosmap::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationPosmap::mapStatuses($request, $this),

                'title' => FoundationPosmap::getPageTitle($request, 'foundation-posmap'),
            ],
        ];
    }
}
