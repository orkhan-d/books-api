<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouriteResource;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserReviewResource;
use App\Models\Favourite;
use App\Models\User;
use App\Traits\ResponseTrait;
use Dotenv\Validator;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ResponseTrait;

    public function profile(Request $request)
    {
        return UserProfileResource::make($request->user());
    }

    public function index($id)
    {
        return UserProfileResource::make(User::query()->find($id));
    }

    public function all()
    {
        return UserProfileResource::collection(User::all());
    }

    public function favourites(Request $request)
    {
        return FavouriteResource::collection(Auth::user()->favourites);
    }

    public function reviews(Request $request)
    {
        return UserReviewResource::collection($this->reviews);
    }

    public function create(Request $request)
    {
        $v = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'login'=>'required|string',
            'password'=>'required|string',
            'admin'=>'boolean'
        ]);
        if ($v->fails())
            return $this->error('Validation error', 422, $v->errors());

        $data = $request->all();
        if ($request->filled('admin'))
            $data['admin'] = 1;
        else
            $data['admin'] = 0;
        $user = User::query()->create($data);

        return \response()->json($user, 201);
    }

    public function edit(Request $request, $id)
    {
        $user = User::query()->find($id);
        $v = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'login'=>'string',
            'password'=>'string',
            'admin'=>'string'
        ]);
        if ($v->fails())
            return $this->error('Validation error', 422, $v->errors());

        $data = $request->all();
        if ($request->filled('admin'))
            $data['admin'] = 1;
        else
            $data['admin'] = 0;
        $user->update($data);
        return \response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::query()->find($id)->delete();

        return response()->json(["message"=>"success"], 200);
    }
}
