@extends('layouts.index')

@section('title', $project->title)

@section('content')
    <div class="container">
        <h2 class="h2 fw-bold">{{ $project->title }}</h2>
        <h4 class="h4">Projekta vadītājs: {{ $project->projectManager->getFullName() }}</h4>
        <div class="card">
            <div class="card-body">{{ $project->description}}</div>
        </div>

        @if($can_alter_project)
            <div class="my-3">
                <a href="{{ route('editProject', $project->id) }}"
                   class="btn btn-outline-secondary d-inline-block position-relative">
                    Rediģēt projektu
                </a>
                <a href="{{ route('createTask') }}"
                   class="btn btn-outline-secondary d-inline-block mx-3">
                    Izveidot uzdevumu
                </a>
            </div>
        @endif

        <hr style="border-color: #{{ $accent_color }}"/>
        <div class="tasks d-flex flex-row flex-wrap w-100 gap-3">
            @foreach($tasks as $task)
                <a class="project card rounded-4 w-25 p-3 d-flex justify-content-between text-decoration-none
                            shadow-sm"
                   style="border-color:#{{ $project->accent_color ?? env('PRIMARY_COLOR') }}"
                   href="{{ route('showTask', $task->id) }}">
                    <div class="headings">
                        <div
                            {{--                            style="background-image: {{ $task->pictures[0] }}" TODO implement--}}
                        >
                            <h4 class="h4" style="color: #{{ $accent_color }}">{{ $task->title }}
                            </h4>
                            <h6 class="h6 fst-italic">Izpildītājs: {{ $task->taskAssignee->getFullName() }}</h6>
                            <div class="badge bg-primary h6 d-inline-block fs-6">{{ $task->taskStatus->description }}</div>
                        </div>
                        <p class="project-description text-truncate text-nowrap">{{ $task->description }}</p>
                    </div>
                    <div class="tooltip-arrow">
                        <svg class="float-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                             fill-rule="evenodd" clip-rule="evenodd">
                            <path
                                d="M21.883 12l-7.527 6.235.644.765 9-7.521-9-7.479-.645.764 7.529
                                    6.236h-21.884v1h21.883z"
                                fill="#{{ $accent_color }}"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
