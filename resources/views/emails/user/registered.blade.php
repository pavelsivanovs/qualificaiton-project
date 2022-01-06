@component('mail::message')
    # Sveicināti, {{ $user->name }}!

    Sistēmā PPIS tika izveidots lietotāja profils ar šiem autentificēšanas datiem:
    - e-pasta adrese: {{ $user->email }}
    - parole: {{ $password }}

    @component('mail::button', ['url' => '/login'])
        Pieteikties sistēmā
    @endcomponent

    Ar cieņu,<br>
    {{ config('app.name') }}
@endcomponent
