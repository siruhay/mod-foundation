<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationWorkunit;
use Module\System\Http\Resources\UserLogActivity;

class WorkunitShowResource extends JsonResource
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
            'record' => FoundationWorkunit::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationWorkunit::mapCombos($request, $this),

                'icon' => FoundationWorkunit::getPageIcon('foundation-workunit'),

                'key' => FoundationWorkunit::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationWorkunit::mapStatuses($request, $this),

                'title' => FoundationWorkunit::getPageTitle($request, 'foundation-workunit'),
            ],
        ];
    }
}
