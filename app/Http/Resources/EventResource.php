<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'content' => $this->content,
            'description' => $this->description,
            'poster' => $this->poster,
            'date' => $this->date,
            'time' => $this->time,
            'category' => new CategoryResource($this->category),
            'city' => new CityResource($this->city)
        ];
    }
}
