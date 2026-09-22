<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HRS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative">
    {{-- page content --}}
    @yield('content')

    {{-- page scripts --}}
    @yield('scripts')

    {{-- alerts --}}
    @include('components.pop-error')
    @include('components.modal-pop')
    @include('components.alert')

    <h1 class="absolute top-2 right-2 p-2 bg-gray-100 rounded-2xl shadow-lg z-50">

    </h1>
</body>
</html>
