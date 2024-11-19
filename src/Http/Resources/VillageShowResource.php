<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationVillage;
use Module\System\Http\Resources\UserLogActivity;

class VillageShowResource extends JsonResource
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
            'record' => FoundationVillage::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationVillage::mapCombos($request, $this),

                'icon' => FoundationVillage::getPageIcon('foundation-village'),

                'key' => FoundationVillage::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationVillage::mapStatuses($request, $this),

                'title' => FoundationVillage::getPageTitle($request, 'foundation-village'),
            ],
        ];
    }
}
