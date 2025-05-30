<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id' => $this['id'],  // from grouped array
            'name' => $this['name'],
            'description' => $this['description'],
            'image' => $this['image'],
            'category_name' => $this['category_name'],
            'tags' => $this['tag_name'],  // array of tag names
        ];
    }
}
