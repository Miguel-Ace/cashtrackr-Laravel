@extends('layouts.auth')

@section('title')
    Nueva Contraseña
@endsection

@section('auth-contents')

  <form method="POST" action="{{route('password.update')}}" class="mt-14 space-y-5" novalidate>
        @csrf

        <x-input-error field="token" />
        <x-input-error field="email" />

        <div class="flex flex-col gap-2">
            <label class="font-bold text-2xl">Password</label>

            <input type="password" placeholder="Nuevo Password" class="w-full border border-gray-300 p-3 rounded-lg"
                name="password" />
        </div>

        <x-input-error field="password" />
        
        <div class="space-y-2">
            <label class="font-bold text-2xl block" for="password_confirmation">Repetir Password</label>

            <input type="password" placeholder="Repite el Nuevo Password"
                class="w-full border border-gray-300 p-3 rounded-lg"
                name="password_confirmation" />
        </div>

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <input type="submit" value='Guardar Password'
            class="bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer" />
    </form>
@endsection