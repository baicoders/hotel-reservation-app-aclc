@extends('layout.staff')

@section('title', 'Edit room ' . $room->rm_number)
@section('eyebrow', 'Rooms')

@section('page')
    <form action="{{ url('/rooms/update/' . $room->id) }}" method="POST" enctype="multipart/form-data" class="card max-w-3xl">
        @csrf
        <div class="p-6">
            @include('staff.rooms.form', ['room' => $room])
        </div>
        <div class="flex flex-col-reverse gap-3 border-t border-line px-6 py-4 sm:flex-row sm:justify-end">
            <a href="{{ url('/rooms/details/' . $room->id) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
@endsection
