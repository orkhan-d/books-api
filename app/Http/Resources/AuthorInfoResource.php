<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'surname'=>$this->surname,
            'name'=>$this->name,
            'patronymic'=>$this->patronymic,
            'books'=>BookInfoResource::collection($this->books)
        ];
    }
}
