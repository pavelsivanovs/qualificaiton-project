<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name'))
    </title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>

    <script>
        $(document).ready(() => {
            const message = sessionStorage.getItem('error-message');

            console.log(sessionStorage);
            console.debug('Message from the server', message);

            if (message) {
                $('#messages').append('<span>' + message + '</span>');
                sessionStorage.removeItem('error-message');
            }
        });
    </script>
</head>
<body class="container min-vh-100 d-flex flex-column justify-content-between">
@auth
    <nav class="navbar justify-content-center">
        <ul class="navbar fs-4 justify-content-center">
            <li class="nav-item logo fw-bold"><a class="nav-link">ISPP</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('showHome') }}">Infopanelis</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('showProjects') }}">Projekti</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('editUser') }}">Profils</a> </li>
            @if (auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('showAdminHome') }}">
                        Administratora panelis
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endauth

<main class="py-4 container">
    <div id="messages"></div>
    @yield('content')
</main>

<footer class="footer bg-gradient mb-0 pt-1">
    <div class="mx-auto text-white">
        <span class="mx-auto">ISPP © {{ \Carbon\Carbon::now()->year }} Visas tiesības aizsargātas.</span>
    </div>
</footer>
</body>
</html>
