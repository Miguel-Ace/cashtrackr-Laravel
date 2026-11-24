<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function index(string $token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function store(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        
        $status = Password::reset(
            $data,
            function($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PasswordReset) {
            return redirect()->route('login')->with('success', 'El password se actualizó correctamente, ya puedes iniciar sesión');
        }

        return back()->withErrors([
            'token' => 'El enlace no es válido o ha expirado. Intenta solicitar otro.'
        ]);
    }
}
