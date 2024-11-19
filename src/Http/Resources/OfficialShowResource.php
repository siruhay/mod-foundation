<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationOfficial;
use Module\System\Http\Resources\UserLogActivity;

class OfficialShowResource extends JsonResource
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
            'record' => FoundationOfficial::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationOfficial::mapCombos($request, $this),

                'icon' => FoundationOfficial::getPageIcon('foundation-official'),

                'key' => FoundationOfficial::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationOfficial::mapStatuses($request, $this),

                'title' => FoundationOfficial::getPageTitle($request, 'foundation-official'),
            ],
        ];
    }
}
