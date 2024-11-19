<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationMember;
use Module\System\Http\Resources\UserLogActivity;

class MemberShowResource extends JsonResource
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
            'record' => FoundationMember::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationMember::mapCombos($request, $this),

                'icon' => FoundationMember::getPageIcon('foundation-member'),

                'key' => FoundationMember::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationMember::mapStatuses($request, $this),

                'title' => FoundationMember::getPageTitle($request, 'foundation-member'),
            ],
        ];
    }
}
