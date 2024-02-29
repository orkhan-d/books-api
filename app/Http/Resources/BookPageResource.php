<?php

namespace App\Http\Resources;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'=>$this->title,
            'image'=>$this->image,
            'description'=>$this->description,
            'authors'=>BookAuthorResource::collection($this->authors),
            'genres'=>BookGenreResource::collection($this->genres),
            'average_rating'=>$this->averageRating(),
            'feedbacks'=>BookFeedbackResource::collection($this->feedbacks)
        ];
    }
}
