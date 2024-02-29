<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    use ResponseTrait;

    public function store(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'rating'=>'required|integer|gte:1|lte:5'
        ]);
        if ($v->fails())
            return $this->error('Validateion error', 422, $v->errors());

        if (Feedback::query()->where('user_id', Auth::user()->id)
            ->firstWhere('book_id', $id)!==null)
            return $this->error('You already have a feedback!', 422);

        return response()->noContent(201);
    }

    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'rating'=>'required|integer|gte:1|lte:5'
        ]);
        if ($v->fails())
            return $this->error('Validateion error', 422, $v->errors());

        Feedback::query()->where('user_id', Auth::user()->id)
            ->firstWhere('book_id', $id)->update([
                'user_id'=>Auth::user()->id,
                'book_id'=>$id,
                'rating'=>$request->rating
            ]);

        return response()->json(['message'=>'success'], 200);
    }

    public function destroy(Request $request, $id)
    {
        Feedback::query()->where('user_id', Auth::user()->id)
            ->firstWhere('book_id', $id)->delete();

        return response()->json(['message'=>'success'], 200);
    }
}
