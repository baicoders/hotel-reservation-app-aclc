@extends('layout.staff')

@section('title', 'Guests')

@section('page')
    <form action="{{ url('/guest') }}" method="GET" role="search" class="card flex flex-col gap-4 p-4 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="query" class="field-label">Search guests</label>
            <input type="search" id="query" name="query" value="{{ $query }}" placeholder="Name or email" class="field-input">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">Search</button>
            @if ($query !== '')
                <a href="{{ url('/guest') }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <p class="mt-6 mb-4 text-sm text-muted">
        {{ $guests->total() }} {{ Str::plural('guest', $guests->total()) }}
        @if ($query !== '') matching "{{ $query }}" @endif
    </p>

    <div class="card relative overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-porcelain text-xs tracking-wider text-muted uppercase">
                <tr>
                    <th scope="col" class="px-5 py-3 font-semibold">Name</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Email</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Phone</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Address</th>
                    <th scope="col" class="px-5 py-3 font-semibold">Added</th>
                    <th scope="col" class="px-5 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($guests as $guest)
                    <tr class="hover:bg-lobby-50">
                        <td class="px-5 py-3 font-medium whitespace-nowrap">{{ $guest->full_name }}</td>
                        <td class="px-5 py-3">{{ $guest->email }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">{{ $guest->phone_number ?: 'None' }}</td>
                        <td class="px-5 py-3 text-muted">{{ $guest->address ?: 'None' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap text-muted tabular-nums">{{ $guest->created_at?->format('M j, Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-4 whitespace-nowrap">
                                <a href="{{ url('/guest/details/' . $guest->id) }}" class="font-semibold text-lobby-700 hover:underline">
                                    Open details<span class="sr-only"> for {{ $guest->full_name }}</span>
                                </a>
                                <form action="{{ url('/guest/delete/' . $guest->id) }}" method="POST" id="guest_delete_{{ $guest->id }}" data-busy-label="Deleting..."
                                    onsubmit="return handleFormSubmission(event, @js($guest->full_name . ' and their ' . $guest->reservations_count . ' ' . Str::plural('reservation', $guest->reservations_count) . ', with their payments, will be deleted. Rooms they are checked in to become available again. This cannot be undone.'), 'Delete guest?', 'guest_delete_{{ $guest->id }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-brick-700 hover:underline">
                                        Delete<span class="sr-only"> {{ $guest->full_name }}</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-muted">
                            {{ $query !== '' ? 'No guests match this search.' : 'No guests yet. Guests are added when they book a room.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $guests->links() }}</div>
@endsection
