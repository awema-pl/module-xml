<?php

namespace AwemaPL\Xml\User\Sections\Sources\Resources;

use AwemaPL\Xml\User\Sections\Sources\Models\Contracts\Printable;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentSource extends JsonResource
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
            'sourceable' => $this->sourceable,
            'provider' =>  $this->sourceable->getProviderName(),
            'source_name' => $this->sourceable->name,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
