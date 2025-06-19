<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationPosmap;
use Illuminate\Http\Resources\Json\JsonResource;

class PosmapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationPosmap::mapResource($request, $this);
    }
}
