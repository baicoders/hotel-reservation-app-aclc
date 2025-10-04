@extends('layout.app')

@section('content')
    <div class="flex flex-col bg-gray-300 ">
        <div class="h-screen p-4 flex justify-center items-center">
            <div class="flex flex-col gap-4 text-center">
                <h1 class="text-2xl font-bold">404</h1>
                <h1 class="text-2xl font-bold">Page Not Found ya Dingus</h1>
                <a href="{{ url('/home') }}" class="text-gray-600 hover:underline">Go back home</a>
            </div>
        </div>
        <!-- Order your soul. Reduce your wants. - Augustine -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('404 page loaded');
    </script>
@endsection
