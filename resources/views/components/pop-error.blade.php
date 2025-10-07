{{-- error --}}
@if ($errors->any())
    <div class="bg-white rounded-xl shadow-lg fixed top-4 right-4 z-50">
        <p class="bg-red-300 p-2 px-3 rounded-t-xl flex justify-between"><strong>Error!</strong> <span
                onclick="closeError()">Close</span></p>
        <ul class="p-2 px-3 rounded-b-xl">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
@endif

<script>
    // auto close after 5 seconds
    const closeError = () => {
        const errorDiv = document.querySelector('.bg-white.rounded-xl.shadow-lg.fixed.top-4.right-4.z-50');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    setTimeout(closeError, 8000);
</script>
