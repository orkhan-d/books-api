<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookAuthorResource;
use App\Http\Resources\BookInfoResource;
use App\Http\Resources\BookPageResource;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\BookGenre;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $v = Validator::make($request->all(), [
            'author_ids' => 'array',
            'author_ids.*' => 'integer',
            'genre_ids' => 'array',
            'genre_ids.*' => 'integer',
            'query' => 'string',
            'min_rating' => 'integer',
            'max_rating' => 'integer',
            'sortBy' => 'string',
        ]);

        $query = Book::query();

        if ($request->filled('query'))
            $query = $query->where('title', 'LIKE', '%' . $request->query . '%');

        if ($request->filled('author_ids')) {
            $query = $query->whereHas('authors', function($query) use($request) {
                $query->whereIn('authors.id', $request->author_ids);
            });
        };

        if ($request->filled('genre_ids')) {
            $query = $query->whereHas('genres', function($query) use($request) {
                $query->whereIn('genres.id', $request->genre_ids);
            });
        };

        $books = $query->get();
        if ($request->filled('min_rating'))
            $books = $books->filter(fn($book) => $book->averageRating()>=$request->min_rating);

        if ($request->filled('max_rating'))
            $books = $books->filter(fn($book) => $book->averageRating()<=$request->min_rating);

        if ($request->filled('sortBy')) {
            if (str_ends_with($request->sortBy, 'Asc'))
                $books = $books->sortBy(fn($b) => $b[str_replace('Asc', '', $request->sortBy)]);
            if (str_ends_with($request->sortBy, 'Desc'))
                $books = $books->sortByDesc(fn($b) => $b[str_replace('Asc', '', $request->sortBy)]);

//            switch ($request->sortBy) {
//                case 'titleAsc':
//                    $books = $books->sortBy(fn($b) => $b->title);
//                    break;
//                case 'titleDesc':
//                    $books = $books->sortByDesc(fn($b) => $b->title);
//                    break;
//            }
        }

        return response()->json([
            'data' => BookInfoResource::collection($books)
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => BookPageResource::make(Book::query()->find($id))
        ]);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'title'=>'required|string',
            'description'=>'required|string',
            'image'=>'required|image|mimes:png,jpg,jpeg',
            'genres'=>'required|array',
            'genres.*'=>'required|int',
            'authors'=>'required|array',
            'authors.*'=>'required|int'
        ]);

        if ($v->fails())
            return $this->error('Validation error!', 422, $v->errors());

        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $data = $request->only(['title', 'description', 'image']);
        $data['image'] = $filename;
        $book = Book::query()->create($data);

        foreach ($request->authors as $author) {
            BookAuthor::query()->create([
                'author_id'=>$author,
                'book_id'=>$book->id
            ]);
        }

        foreach ($request->genres as $genre) {
            BookGenre::query()->create([
                'genre_id'=>$genre,
                'book_id'=>$book->id
            ]);
        }

        return response()->json(BookInfoResource::make($book), 201);
    }

    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'title'=>'required|string',
            'description'=>'required|string',
            'image'=>'required|image|mimes:png,jpg,jpeg',
            'genres'=>'required|array',
            'genres.*'=>'required|int',
            'authors'=>'required|array',
            'authors.*'=>'required|int'
        ]);

        if ($v->fails())
            return $this->error('Validation error!', 422, $v->errors());

        $book = Book::query()->find($id);

        BookGenre::query()->where('book_id', $book->id)->delete();
        BookAuthor::query()->where('book_id', $book->id)->delete();

        $file = $request->file('image');
        $filename = time() . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $data = $request->only(['title', 'description', 'image']);
        $data['image'] = $filename;
        $book->update($data);

        foreach ($request->authors as $author) {
            BookAuthor::query()->create([
                'author_id'=>$author,
                'book_id'=>$book->id
            ]);
        }

        foreach ($request->genres as $genre) {
            BookGenre::query()->create([
                'genre_id'=>$genre,
                'book_id'=>$book->id
            ]);
        }

        return response()->json(BookInfoResource::make($book), 200);
    }

    public function destroy($id)
    {
        Book::query()->find($id)->delete();
        return response()->json(['message'=>'success'], 200);
    }
}
