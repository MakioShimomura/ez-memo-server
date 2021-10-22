<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:10',
            'email' => 'required|email:rfc,dns|unique:App\Models\User,email',
            'password' => 'required|confirmed|between:8,16',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password')); // ハッシュ化するためのヘルパー

        $user->save();

        return response()->json([
            'status' => 'OK',
            'user_id' => $user->id,
            'accessToken' => $user->createToken('Token')->accessToken,
        ]);
    }
}
