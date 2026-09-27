@extends('layout.staff')

@section('title', 'Add room')
@section('eyebrow', 'Rooms')
@section('inline-errors', true)

@section('page')
    <form action="{{ url('/rooms/record') }}" method="POST" enctype="multipart/form-data" class="card max-w-3xl">
        @csrf
        <div class="p-6">
            @include('staff.rooms.form')
        </div>
        <div class="flex flex-col-reverse gap-3 border-t border-line px-6 py-4 sm:flex-row sm:justify-end">
            <a href="{{ url('/rooms') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Add room</button>
        </div>
    </form>
@endsection
