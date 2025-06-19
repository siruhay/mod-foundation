<?php

namespace Module\Foundation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Module\Foundation\Models\FoundationOrganization;
use Module\System\Http\Resources\UserLogActivity;

class OrganizationShowResource extends JsonResource
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
            'record' => FoundationOrganization::mapResourceShow($request, $this),

            /**
             * the page setups
             */
            'setups' => [
                'combos' => FoundationOrganization::mapCombos($request, $this),

                'icon' => FoundationOrganization::getPageIcon('foundation-organization'),

                'key' => FoundationOrganization::getDataKey(),

                'logs' => $request->activities ? UserLogActivity::collection($this->activitylogs) : null,

                'softdelete' => $this->trashed() ?: false,

                'statuses' => FoundationOrganization::mapStatuses($request, $this),

                'title' => FoundationOrganization::getPageTitle($request, 'foundation-organization'),
            ],
        ];
    }
}
