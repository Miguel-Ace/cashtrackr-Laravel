<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UpdateProfileController extends Controller
{
    public function edit()
    {
        return Inertia::render('Settings/UpdateProfile');
    }
    
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return back()->with('success', 'Perfil actualizado correctamente');
    }
}
