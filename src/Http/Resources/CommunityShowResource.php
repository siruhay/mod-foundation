<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationCommunity;
use Module\System\Http\Resources\UserLogActivity;

class CommunityShowResource extends JsonResource
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
            'record' => FoundationCommunity::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationCommunity::mapCombos($request, $this),

                'icon' => FoundationCommunity::getPageIcon('foundation-community'),

                'key' => FoundationCommunity::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationCommunity::mapStatuses($request, $this),

                'title' => FoundationCommunity::getPageTitle($request, 'foundation-community'),
            ],
        ];
    }
}
