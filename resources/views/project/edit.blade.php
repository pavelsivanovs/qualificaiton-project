@extends('layouts.index')

@section('title', $project->title)

@section('content')
    {{--    {{ $project->title }}--}}
    {{--    {{ $accent_color }}--}}
    {{--    @foreach($tasks as $task)--}}
    {{--        {{ $task->title }}--}}
    {{--    @endforeach--}}
    <div class="container">
        <x-under-construction></x-under-construction>
    </div>
@endsection
