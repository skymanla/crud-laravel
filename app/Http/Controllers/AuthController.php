<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $attr = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email',
                'password' => 'required|string|min:6'
            ]
        );

        User::create(
            [
                'name' => $attr['name'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email']
            ]
        );

        return $this->success(
            [],
            '회원가입이 되었습니다'
        );
    }

    public function login(Request $request)
    {
        // 기존 토큰 제거
        if (auth()->user()) {
            auth()->user()->tokens()->delete();
        }

        $attr = $request->validate(
            [
                'email' => 'required|string|email|',
                'password' => 'required|string|min:6'
            ]
        );

        if (!Auth::attempt($attr)) {
            return $this->error([], '회원정보가 없습니다', 401);
        }

        return $this->success(
            [
                'token' => auth()->user()->createToken('API Token')->plainTextToken
            ]
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
