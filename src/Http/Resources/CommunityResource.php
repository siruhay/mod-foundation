<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationCommunity;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationCommunity::mapResource($request, $this);
    }
}
