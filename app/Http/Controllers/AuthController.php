<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required | min:5'
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        if($user->save()) {
            $user->signin = [
                'href' => 'api/v1/signin',
                'method' => 'POST',
                'params' => 'email, password'
            ];
            $response = [
                'msg' => 'user created',
                'user' => $user
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'msg' => 'An error occurred'
            ];
            return response()->json($response, 404);
        }

    }

    public function signin(Request $request)
    {
        return 'ini berhasil';
    }
}
