@if ($errors->any())
    <div data-toast role="alert"
        class="w-full rounded-xl border border-brick-700/20 bg-brick-100 p-4 text-brick-700 shadow-lg">
        <div class="flex items-start gap-3">
            <p class="flex-1 text-sm font-semibold">Please fix the following:</p>
            <button type="button" data-toast-close class="text-sm font-semibold underline-offset-2 hover:underline">
                Dismiss
            </button>
        </div>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
