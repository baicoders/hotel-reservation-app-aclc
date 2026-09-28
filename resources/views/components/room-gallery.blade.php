@php $count = count($images); @endphp
@if ($count)
    <div id="gallery" class="flex aspect-[4/3] snap-x snap-mandatory overflow-x-auto scroll-smooth">
        @foreach ($images as $image)
            <div class="relative w-full shrink-0 snap-start bg-lobby-50">
                <span aria-hidden="true" class="absolute inset-0 flex items-center justify-center text-sm text-muted">Photo unavailable</span>
                <img src="{{ asset('storage/' . $image->photo_path) }}" alt="Room {{ $roomNumber }}, photo {{ $loop->iteration }} of {{ $count }}"
                    onerror="this.previousElementSibling.removeAttribute('aria-hidden'); this.remove()" class="absolute inset-0 size-full object-cover">
            </div>
        @endforeach
    </div>
    @if ($count > 1)
        <div class="flex items-center justify-between border-t border-line px-4 py-3">
            <button type="button" data-gallery-step="-1" class="btn btn-secondary">Previous</button>
            <p id="gallery-counter" class="text-sm text-muted" aria-live="polite">Photo 1 of {{ $count }}</p>
            <button type="button" data-gallery-step="1" class="btn btn-secondary">Next</button>
        </div>
        <script>
            (() => {
                const gallery = document.getElementById('gallery');
                const counter = document.getElementById('gallery-counter');
                const total = gallery.children.length;
                const current = () => Math.round(gallery.scrollLeft / gallery.clientWidth);
                document.querySelectorAll('[data-gallery-step]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const next = (current() + Number(button.dataset.galleryStep) + total) % total;
                        const wraps = Math.abs(next - current()) > 1;
                        gallery.scrollTo({ left: next * gallery.clientWidth, behavior: wraps ? 'instant' : 'smooth' });
                    });
                });
                let shown = 0;
                gallery.addEventListener('scroll', () => {
                    if (current() === shown) return;
                    shown = current();
                    counter.textContent = `Photo ${shown + 1} of ${total}`;
                });
            })();
        </script>
    @endif
@else
    <div class="flex aspect-[4/3] flex-col items-center justify-center gap-2 bg-lobby-50 text-center">
        <p class="font-semibold">No photos yet</p>
        @isset($emptyLink)
            <a href="{{ $emptyLink }}" class="text-sm font-semibold text-lobby-700 hover:underline">Add photos</a>
        @endisset
    </div>
@endif
