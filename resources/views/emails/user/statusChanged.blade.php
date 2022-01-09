@component('mail::message')
# Sveicināti!

Jūsu pieteikums uz lietotāja statusa maiņa tika apstrādāts.

@component('mail::table')
| Vecs statuss | Jauns statuss |
| -------------- | ------------- |
| {{ $old_status->status }} | {{ $new_status->status }} |
@endcomponent

Ar cieņu,<br>
{{ config('app.name') }}
@endcomponent
