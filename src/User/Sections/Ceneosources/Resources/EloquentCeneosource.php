<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentCeneosource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
