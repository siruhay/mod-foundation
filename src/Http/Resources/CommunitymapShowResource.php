<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationCommunitymap;
use Module\System\Http\Resources\UserLogActivity;

class CommunitymapShowResource extends JsonResource
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
            'record' => FoundationCommunitymap::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationCommunitymap::mapCombos($request, $this),

                'icon' => FoundationCommunitymap::getPageIcon('foundation-communitymap'),

                'key' => FoundationCommunitymap::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationCommunitymap::mapStatuses($request, $this),

                'title' => FoundationCommunitymap::getPageTitle($request, 'foundation-communitymap'),
            ],
        ];
    }
}
