<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationCommunitymap;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunitymapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationCommunitymap::mapResource($request, $this);
    }
}
