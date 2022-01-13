@extends('layouts.index')

@section('title', 'Jauns projekts')

@section('content')
    {{ $project->title }}
    {{ $accent_color }}
    @foreach($tasks as $task)
        {{ $task->title }}
    @endforeach
@endsection
