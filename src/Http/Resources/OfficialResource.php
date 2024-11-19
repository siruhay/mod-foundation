<?php

namespace Module\Foundation\Http\Resources;

use Module\Foundation\Models\FoundationOfficial;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return FoundationOfficial::mapResource($request, $this);
    }
}
