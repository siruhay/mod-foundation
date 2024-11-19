<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationPosition;
use Module\System\Http\Resources\UserLogActivity;

class PositionShowResource extends JsonResource
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
            'record' => FoundationPosition::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationPosition::mapCombos($request, $this),

                'icon' => FoundationPosition::getPageIcon('foundation-position'),

                'key' => FoundationPosition::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationPosition::mapStatuses($request, $this),

                'title' => FoundationPosition::getPageTitle($request, 'foundation-position'),
            ],
        ];
    }
}
