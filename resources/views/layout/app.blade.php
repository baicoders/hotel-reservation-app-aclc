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
<body>
    {{-- page content --}}
    @yield('content')

    {{-- page scripts --}}
    @yield('scripts')
</body>
</html>
