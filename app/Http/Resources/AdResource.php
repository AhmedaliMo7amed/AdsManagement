<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
            'id' => $this->id,
            'advertiser_id' => $this->advertiser_id,
            'advertiser' => new AdvsResource($this->advertiser),
            'type' => $this->type,
            'title' => $this->title,
            'category' => $this->category,
            'tags' =>  $this->tags,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d')
        ];
    }
}
