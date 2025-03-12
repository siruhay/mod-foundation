<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationBiodata;
use Illuminate\Http\Resources\Json\JsonResource;

class BiodataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationBiodata::mapResource($request, $this);
    }
}
