@extends('layout.app')

@section('title', 'Page not found')

@section('content')
    <main class="flex min-h-screen items-center justify-center px-4 py-16">
        <div class="max-w-md text-center">
            <div class="flex justify-center">@include('components.key-tag', ['number' => '404', 'size' => 'lg'])</div>
            <h1 class="page-title mt-6">Page not found</h1>
            <p class="mt-3 text-muted">This page does not exist or was moved. Check the address, or go back to the start.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/home') }}" class="btn btn-secondary">Go to home</a>
                <a href="{{ url('/search-room') }}" class="btn btn-primary">Browse rooms</a>
            </div>
        </div>
    </main>
@endsection
