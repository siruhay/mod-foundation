<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationOrganization;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationOrganization::mapResource($request, $this);
    }
}
