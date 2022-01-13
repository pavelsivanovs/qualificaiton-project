@extends('layouts.index')

@section('title', $task->title)

@section('content')
    <div class="container">
        <h2 class="h2 fw-bold">{{ $task->title }}</h2>
        <h4 class="h4">
            Projekts: <a href="{{ route('showProject', $task->taskProject ) }}"
                         style="color: #{{ $accent_color }}">{{ $task->taskProject->title }}</a>
        </h4>

        @if ($can_edit)
            <a href="{{ route('editTask', $task->id) }}" class="btn btn-outline-secondary mb-3 float-end">Rediģēt uzdevumu</a>
        @endif

        <div class="task-body">
            <div class="card task-description">
                <div class="card-body">{{ $task->description}}</div>
            </div>
            <div class="task-info d-flex flex-column">
                <div class="mb-2">
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-primary dropdown-toggle fs-5" type="button" id="taskStatuses"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $task->taskStatus->description }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="taskStatuses">
                            @foreach ($task_statuses as $task_status)
                                <li>
                                    <a class="dropdown-item {{ $task_status->id == $task->status ? 'disabled' : '' }}"
                                       href="{{ route('changeStatus', [$task->id, $task_status->id]) }}"
                                    >
                                        {{ $task_status->description }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="fs-5">
                    <span class="fw-bold">Izpildītājs</span>: <x-user-label :user="$task->assignee"
                                                                            class="d-inline-block"></x-user-label>
                </div>
                <div class="fs-5">
                    <span class="fw-bold">Termiņš</span>:
                    {{ date('d/m/Y', strtotime($task->deadline)) }}
                </div>
            </div>
        </div>

        @if ($pictures)
            <div class="task-images">
                <div class="h5">
                    Uzdevumam pievienotie attēli
                </div>
                {{--            TODO implement images--}}
            </div>
        @endif

        <hr style="border-color: #{{ $accent_color }}"/>

        <div class="add-comment-area mb-3">
            <form method="POST" action="{{ route('addComment', $task->id) }}" class="needs-verification">
                @csrf
                    <textarea id="comment-text" name="comment" placeholder="Ievadiet komentāru" rows="4"
                              class="form-control mb-2"></textarea>
                <button type="submit" class="btn btn-primary">Pievienot komentāru</button>
            </form>
        </div>

        <hr style="border-color: #{{ $accent_color }}"/>

        <div class="task-comments d-flex flex-column gap-4 w-75">
            @foreach ($comments as $comment)
                <div class="comment ps-3" style="border-left-color: #{{ $accent_color }}">
                    <div>
                        <x-user-label :user="$comment->author" class="d-inline-block"/>
                        <span class="fst-italic">
                            {{ date('H:m d/m/Y', strtotime($comment->created_at)) }}
                        </span>:
                    </div>
                    <div class="ms-4 rounded-6">{{ $comment->comment }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
