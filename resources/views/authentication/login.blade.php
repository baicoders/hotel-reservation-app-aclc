@extends('layout.app')

@section('title', 'Staff sign in')
@section('inline-errors', true)

@section('content')
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="hidden bg-lobby-950 bg-cover bg-center p-12 text-white lg:flex lg:flex-col lg:justify-end"
            style="background-image: linear-gradient(rgb(8 36 35 / 0.55), rgb(8 36 35 / 0.9)), url('{{ asset('aclc_building_img.jpg') }}');">
            <a href="{{ url('/home') }}" class="text-5xl leading-none">@include('components.title')</a>
            <p class="mt-4 max-w-md text-lobby-100">Front desk access for rooms, guests, reservations and payments.</p>
        </div>

        <main class="flex items-center justify-center px-4 py-12 sm:px-8">
            <form method="POST" action="{{ url('/login') }}" data-busy-label="Signing in..." class="w-full max-w-sm">
                @csrf
                <a href="{{ url('/home') }}" class="font-display text-2xl font-extrabold tracking-wide text-brass-600 uppercase lg:hidden">ACLC</a>
                <h1 class="page-title mt-2">Staff sign in</h1>
                <p class="mt-2 text-muted">Use the username and password the front desk gave you.</p>

                <div class="mt-8">
                    <label for="username" class="field-label">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        autocomplete="username" class="field-input"
                        @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>
                    @error('username')<p id="username-error" role="alert" class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="mt-5">
                    <label for="password" class="field-label">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password" class="field-input"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')<p id="password-error" role="alert" class="field-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn btn-primary mt-8 w-full py-3">Sign in</button>
                <a href="{{ url('/home') }}" class="mt-6 block text-center text-sm font-semibold text-lobby-700 hover:underline">Back to home</a>
            </form>
        </main>
    </div>
@endsection
