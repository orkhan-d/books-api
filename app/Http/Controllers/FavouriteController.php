<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Feedback;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends Controller
{
    use ResponseTrait;

    public function store(Request $request, $id)
    {
        Favourite::query()->create([
            'user_id'=>Auth::user()->id,
            'book_id'=>$id,
        ]);

        return response()->noContent(201);
    }

    public function destroy(Request $request, $id)
    {
        Favourite::query()->where('user_id', Auth::user()->id)
            ->firstWhere('book_id', $id)->delete();

        return response()->json(['message'=>'success'], 200);
    }
}
