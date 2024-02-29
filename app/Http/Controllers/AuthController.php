<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ResponseTrait;

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'login'=>'required|string|regex:/^[a-zA-Z]+$/',
            'password'=>'required|confirmed|string|regex:/^(?=[a-z]*[\$\!\#\@]).{6,20}$/i',
        ]);

        if ($v->fails())
            return $this->error('Validation error', 422, $v->errors());

        User::query()->create($request->except('password_confirmation'));

        return response()->noContent( 201);
    }

    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'login'=>'required|string',
            'password'=>'required|string',
        ]);

        if ($v->fails())
            return $this->error('Validation error', 422, $v->errors());

        $user = User::query()->where('login', $request->login)
            ->firstWhere('password', $request->password);

        if (!$user)
            return $this->error('Login failed!', 403);

        $user->update([
            'token'=>Str::uuid()
        ]);

        return response()->json(['user'=>$user],  200);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        Auth::logout();
        User::query()->firstWhere('token', $token)->update(['token'=>null]);

        return response()->json(['message'=>'success'],  200);
    }
}
