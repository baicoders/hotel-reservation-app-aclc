@if (session('success') || session('error') || $errors->any())
    <div class="fixed inset-x-4 top-4 z-40 flex flex-col items-end gap-3 sm:left-auto sm:w-96">
        @if (session('success'))
            <div data-toast role="status"
                class="flex w-full items-start gap-3 rounded-xl border border-moss-700/20 bg-moss-100 p-4 text-moss-700 shadow-lg">
                <p class="flex-1 text-sm font-medium">{{ session('success') }}</p>
                <button type="button" data-toast-close class="text-sm font-semibold underline-offset-2 hover:underline">
                    Dismiss
                </button>
            </div>
        @endif

        @if (session('error'))
            <div data-toast role="alert"
                class="flex w-full items-start gap-3 rounded-xl border border-brick-700/20 bg-brick-100 p-4 text-brick-700 shadow-lg">
                <p class="flex-1 text-sm font-medium">{{ session('error') }}</p>
                <button type="button" data-toast-close class="text-sm font-semibold underline-offset-2 hover:underline">
                    Dismiss
                </button>
            </div>
        @endif

        @include('components.pop-error')
    </div>

    <script>
        document.querySelectorAll('[data-toast]').forEach((toast) => {
            toast.querySelector('[data-toast-close]').addEventListener('click', () => toast.remove());
        });
    </script>
@endif
