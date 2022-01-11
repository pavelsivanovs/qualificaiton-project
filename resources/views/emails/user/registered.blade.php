@component('mail::message')
# Sveicināti, {{ $user->name }}!

Sistēmā PPIS tika izveidots lietotāja profils ar šiem autentificēšanas datiem:
- e-pasta adrese: {{ $user->email }}
- parole: {{ $password }}

@component('mail::button', ['url' => route('showLogin')])
Pieteikties sistēmā
@endcomponent

Ar cieņu,<br>
{{ config('app.name') }}
@endcomponent
