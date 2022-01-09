 @component('mail::message')
# Sveicināti!

{{ $comment_author->name }} {{ $comment_author->surname }} pievienoja jaunu komentāru uzdevumā

@component('mail::panel')
{{ $task->title }}
@endcomponent

@component('mail::button', ['url' => $task_url, 'color' => 'primary'])
Apskatīt
@endcomponent

Ar cieņu,<br>
{{ config('app.name') }}
@endcomponent
