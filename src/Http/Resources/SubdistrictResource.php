<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationSubdistrict;
use Illuminate\Http\Resources\Json\JsonResource;

class SubdistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationSubdistrict::mapResource($request, $this);
    }
}
