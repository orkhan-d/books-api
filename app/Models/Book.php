<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function averageRating()
    {
        $feedbacks = $this->hasMany(Feedback::class, 'book_id')->get('rating')->toArray();
        if (count($feedbacks)===0)
            return 0;
        return array_sum(array_map(fn ($el) => $el['rating'], $feedbacks))/count($feedbacks);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'book_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'book_id', 'genre_id');
    }
}
