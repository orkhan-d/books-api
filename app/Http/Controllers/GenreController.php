<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Genre::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'=>'string|required'
        ]);

        if ($v->fails())
            $this->error('Validation error', 422, $v->errors());

        $genre = Genre::query()->create($request->all());

        return response()->json($genre,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $v = Validator::make($request->all(), [
            'name'=>'string|required'
        ]);

        if ($v->fails())
            $this->error('Validation error', 422, $v->errors());

        $genre = Genre::query()->find($id);
        $genre->update($request->all());

        return response()->json($genre,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genre::query()->find($id)->delete();
        return response()->json(['message'=>'success'], 200);
    }
}
