@php
    $room = $room ?? null;
    $status = old('status', strtolower($room->status ?? 'available'));
    $invalid = fn ($field) => $errors->has($field) ? 'aria-invalid=true aria-describedby=' . $field . '-error' : '';
@endphp
<div class="grid gap-5 sm:grid-cols-2">
    <div>
        @if ($room)
            <p class="field-label">Room number</p>
            @include('components.key-tag', ['number' => $room->rm_number])
            <p class="mt-1 text-sm text-muted">Room numbers cannot be changed.</p>
        @else
            <label for="roomNumber" class="field-label">Room number</label>
            <input type="text" id="roomNumber" name="roomNumber" value="{{ old('roomNumber') }}" required maxlength="13"
                class="field-input" {{ $invalid('roomNumber') }}>
            @error('roomNumber')<p id="roomNumber-error" class="field-error">{{ $message }}</p>@enderror
        @endif
    </div>

    <div>
        <label for="roomType" class="field-label">Room type</label>
        <input type="text" id="roomType" name="roomType" value="{{ old('roomType', $room->rm_type ?? '') }}" required
            maxlength="255" list="room-types" class="field-input" {{ $invalid('roomType') }}>
        <datalist id="room-types"><option value="Single"><option value="Double"><option value="Suite"></datalist>
        @error('roomType')<p id="roomType-error" class="field-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="roomPrice" class="field-label">Price per night ($)</label>
        <input type="number" id="roomPrice" name="roomPrice" value="{{ old('roomPrice', $room->rm_price_per_night ?? '') }}"
            required min="0" max="99999.99" step="0.01" inputmode="decimal" class="field-input" {{ $invalid('roomPrice') }}>
        @error('roomPrice')<p id="roomPrice-error" class="field-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="status" class="field-label">Status</label>
        <select id="status" name="status" class="field-input" {{ $invalid('status') }}>
            @foreach (['available' => 'Available', 'unavailable' => 'Unavailable', 'maintenance' => 'Maintenance'] as $value => $label)
                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<p id="status-error" class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="roomPhotos" class="field-label">{{ $room ? 'Add photos' : 'Photos' }}</label>
        <input type="file" id="roomPhotos" name="roomPhotos[]" multiple accept="image/jpeg,image/png,image/gif"
            aria-describedby="roomPhotos-hint{{ $errors->hasAny(['roomPhotos', 'roomPhotos.*']) ? ' roomPhotos-error' : '' }}"
            class="block w-full text-sm text-muted file:mr-4 file:rounded-lg file:border-0 file:bg-lobby-900 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-lobby-700">
        <p id="roomPhotos-hint" class="mt-1 text-sm text-muted">JPG, PNG or GIF, up to 4 MB each.</p>
        @if ($errors->hasAny(['roomPhotos', 'roomPhotos.*']))
            <p id="roomPhotos-error" class="field-error">{{ $errors->first('roomPhotos') ?: $errors->first('roomPhotos.*') }}</p>
        @endif
        <ul id="photo-previews" class="mt-3 grid grid-cols-3 gap-3 sm:grid-cols-5"></ul>
    </div>
</div>

<script>
    document.getElementById('roomPhotos').addEventListener('change', (event) => {
        const list = document.getElementById('photo-previews');
        list.querySelectorAll('img').forEach((img) => URL.revokeObjectURL(img.src));
        list.replaceChildren(...[...event.target.files].map((file) => {
            const item = document.createElement('li');
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.className = 'aspect-square w-full rounded-lg object-cover ring-1 ring-line';
            item.append(img);
            return item;
        }));
    });
</script>
