<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') | @endif ACLC Hotel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=Figtree:wght@400;500;600;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    @yield('content')

    @include('components.alert')
    @include('components.modal-pop')

    <script>
        const lockedButtons = new Map();
        window.lockForm = (form) => {
            form.querySelectorAll('button[type=submit], button:not([type])').forEach((button) => {
                lockedButtons.set(button, [...button.childNodes]);
                button.textContent = form.dataset.busyLabel || 'Saving...';
                button.disabled = true;
            });
        };
        document.addEventListener('submit', (event) => {
            if (!event.defaultPrevented && event.target.method.toLowerCase() === 'post') {
                window.lockForm(event.target);
            }
        });
        window.addEventListener('pageshow', (event) => {
            if (!event.persisted) return;
            lockedButtons.forEach((children, button) => {
                button.replaceChildren(...children);
                button.disabled = false;
            });
            lockedButtons.clear();
        });
    </script>

    @yield('scripts')
</body>
</html>
