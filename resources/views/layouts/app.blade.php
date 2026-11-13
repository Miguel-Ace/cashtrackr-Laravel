@extends('layouts.base')

@section('contents')
    <div class="max-w-5xl mx-auto p-5 lg:p-10">
        @yield('actions')
    </div>

    <main class="mt-5 max-w-5xl mx-auto p-5 lg:p-10 mb-20">
        <h1 class="font-bold text-4xl">@yield('title')</h1>
        @yield('dashboard-contents')
    </main>
@endsection