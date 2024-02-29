<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorInfoResource;
use App\Models\Author;
use App\Models\Genre;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AuthorInfoResource::collection(Author::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'surname'=>'string|required',
            'name'=>'string|required',
            'patronymic'=>'string|required',
        ]);

        if ($v->fails())
            return $this->error('Validation error', 422, $v->errors());

        $author = Author::query()->create($request->all());

        return response()->json(AuthorInfoResource::make($author),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return AuthorInfoResource::collection(Author::query()->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $v = Validator::make($request->all(), [
            'surname'=>'string|required',
            'name'=>'string|required',
            'patronymic'=>'string|required',
        ]);

        if ($v->fails())
            return $this->error('Validation error', 422, $v->errors());

        $author = Author::query()->find($id);
        $author->update($request->all());

        return response()->json(AuthorInfoResource::make($author),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Author::query()->find($id)->delete();
        return response()->json(['message'=>'success'], 200);
    }
}
