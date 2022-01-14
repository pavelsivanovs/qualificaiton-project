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
    <link rel="icon" type="image/png" href="{{ asset('layers.png') }}">

    <script type="text/javascript" src="{{ asset('js/index.js') }}"></script>

    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, option)
        });

        $(document).ready(function () {
            var errorMessage = '{{ session('error') }}';
            var message = '{{ session('message') }}';

            console.log(sessionStorage);
            console.debug('Error from the server', errorMessage);
            console.debug('Message from the server', message);

            if (message || errorMessage) {
                var notification = $('#notification');
                var toast = new bootstrap.Toast(notification);

                toast.show();
            }

            message = sessionStorage.getItem('message');
            console.log('Message from the server', message);
        });
    </script>

    @stack('scripts')
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
            <li class="nav-item"><a class="btn btn-outline-secondary"
                                    href="{{ route('logout') }}">Atteikties no sistēmas</a></li>
        </ul>
    </nav>
@endauth

<main class="py-4 container">
    <div id="messages" style="z-index: 11">
        @if (session('error') || session('message'))
            <div id="notification"
                 class="toast align-items-center text-white {{ session('error') ? 'bg-danger' : 'bg-info' }} border-0"
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') ?? session('message') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    @yield('content')
</main>

<footer class="footer bg-gradient mb-0 pt-1">
    <div class="mx-auto text-white">
        <span class="mx-auto">ISPP © {{ \Carbon\Carbon::now()->year }} Visas tiesības aizsargātas.</span>
    </div>
</footer>
</body>
</html>
