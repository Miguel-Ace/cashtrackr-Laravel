<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UpdatePasswordController extends Controller
{
    public function edit()
    {
        return Inertia::render('Settings/UpdatePassword');
    }

    public function update(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return back()->with('success','Password actualizado correctament');
    }
}
