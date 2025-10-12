@extends('layout.app')

@section('content')
    <div class="flex flex-col bg-gray-300 ">

        <div class="h-screen p-4">
            <div class="flex flex-col lg:flex-row gap-4 h-full w-full">
                <div class="rounded-xl p-4 h-full basis-full flex justify-center items-center bg-cover bg-center bg-blend-multiply bg-gray-500 bg-fixed text-gray-300 relative" style="background-image: url('{{ asset('aclc_building_img.jpg') }}');">
                    <a href="{{ url('/home') }}" class="text-2xl font-bold ">
                        @include('components.title')
                    </a>
                </div>
                <div class="bg-white rounded-xl p-4 flex justify-center items-center gap-4 basis-auto">
                    <form method="POST" action="{{ url('/login') }}" class="flex flex-col gap-2 w-96">
                        @csrf
                        
                        <h2 class="text-2xl font-bold text-center">Login</h2>
                        @if (session('error'))
                            <div class="bg-red-100 text-red-700 p-2 rounded">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div>
                            <label for="username" class="block text-gray-700">Username:</label>
                            <input type="text" id="username" name="username" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring focus:border-gray-300"
                                value="{{ old('username') }}">
                            @error('username')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-gray-700">Password:</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring focus:border-gray-300">
                            @error('password')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-gray-500 text-white px-3 py-2 rounded-xl hover:bg-gray-600 transition duration-200 mt-3">
                            Login
                        </button>

                        {{-- <div class="bg-gray-300 rounded-xl p-4 h-full basis-full flex justify-center items-center">
                            Some Info Here
                        </div> --}}
                    </form>
                </div>
            </div>

        </div>
        <!-- Well begun is half done. - Aristotle -->
    </div>
@endsection

@section('scripts')
    <script>
        console.log('Login page loaded');
    </script>
@endsection
