@extends('layouts.index')

@section('title',  'Projekti')

@section('content')
    <div class="container d-flex flex-column justify-content-center">
        <div class="m-2">
            <div class="">
                <h2 class="h2 d-inline-block">Projekti</h2>
                @if (auth()->user()->isProjectManager() || auth()->user()->isAdmin())
                    <a href="{{ route('createProject') }}" class="btn btn-outline-secondary d-inline-block mb-2 mx-3">
                        Izveidot jaunu projektu
                    </a>
                @endif
            </div>
            <hr/>
            <div class="projects d-flex flex-wrap gap-3">
                @foreach($projects as $project)
                    <a class="project card rounded-4 w-25 p-3 d-flex justify-content-between text-decoration-none
                            shadow-sm"
                       style="border-color:#{{ $project->accent_color ?? env('PRIMARY_COLOR') }}"
                       href="{{ route('showProject', $project->id) }}">
                        <div class="headings">
                            <div style="background-image: {{ $project->icon }}">
                                <h4 class="h4">{{ $project->title }}</h4>
                                <h6 class="h6 fst-italic">PM: {{ $project->projectManager->getFullName() }}</h6>
                            </div>
                            <p class="project-description text-truncate text-nowrap">{{ $project->description }}</p>
                        </div>
                        <div class="tooltip-arrow">
                            <svg class="float-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                                 fill-rule="evenodd" clip-rule="evenodd">
                                <path
                                    d="M21.883 12l-7.527 6.235.644.765 9-7.521-9-7.479-.645.764 7.529
                                    6.236h-21.884v1h21.883z"
                                    fill="#{{ $project->accent_color ?? env('PRIMARY_COLOR') }}"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
@endsection
