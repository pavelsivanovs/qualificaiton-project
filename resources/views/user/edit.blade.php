@extends('layouts.index')

@section('title', 'Apskatīt profilu')

@section('content')
    <div class="container">
        <h2 class="h2 fw-bold">Profils</h2>

        <div class="user-info">
            @if ($user->profilePicture)
                <div class="profile-picture">
                    <img src="{{ asset($user->profilePicture->path) }}" alt="Lietotāja profila bilde"/>
                </div>
            @endif
            <div class="">
                <span class="fw-bold">Vārds</span>: {{ $user->name }}
            </div>
            <div class="">
                <span class="fw-bold">Uzvārds</span>: {{ $user->surname }}
            </div>
            <div class="">
                <span class="fw-bold">E-pasta adrese</span>: {{ $user->email }}
            </div>
            <div class="">
                <span class="fw-bold">Lietotāja statuss</span>: {{ $user->userStatus->status }}
                <button id="changeRequestButton" class="btn btn-outline-secondary ms-3" data-bs-toggle="modal"
                        data-bs-target="#changeRequestModal">
                    Pieteikt jaunu statusu
                </button>

                <div class="modal fade" id="changeRequestModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Pieteikt jaunu statusu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('statusChangeRequest') }}">
                                @csrf
                                <div class="modal-body">
                                    <label for="new_status">
                                        <select name="new_status" class="form-select">
                                            <option selected disabled>Izvēlējieties jaunu statusu</option>
                                            @foreach ($user_statuses as $status)
                                                <option value="{{ $status->id }}">{{ $status->status }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Pieteikt</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aizvērt</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$user->isAdmin())
                <a href="{{ route('deactivationRequest') }}" class="btn btn-outline-danger">Pieteikties uz profila izslēgšanu</a>
            @endif
        </div>

        <hr />

        <div class="user-edit-info">
            <h2 class="h2 fw-bold">Profila rediģēšana</h2>
            <div class="edit-form">
                <form method="POST" action="{{ route('editUser') }}">
                    @csrf

                    <div class="form-row my-3">
                        <label for="name" class="form-label">Vārds</label>
                        <input type="text" id="name" class="form-control" name="name">

                        @if ($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-row my-3">
                        <label for="surname" class="form-label">Uzvārds</label>
                        <input type="text" id="surname" class="form-control" name="surname">

                        @if ($errors->has('surname'))
                            <div class="text-danger">{{ $errors->first('surname') }}</div>
                        @endif
                    </div>

                    <div class="form-row my-3">
                        <label for="email" class="form-label">E-pasts</label>
                        <input type="email" id="email" class="form-control" name="email">

                        @if ($errors->has('email'))
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-row my-3">
                        <label for="password" class="form-label">Parole</label>
                        <input type="password" id="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <div class="text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-row my-3">
                        <label for="password_repeated" class="form-label">Parole atkārtoti</label>
                        <input type="password" id="password_repeated" class="form-control" name="password_repeated">

                        @if ($errors->has('password_repeated'))
                            <div class="text-danger">{{ $errors->first('password_repeated') }}</div>
                        @endif
                    </div>

{{--                    <div class="form-row my-3">--}}
{{--                        <label for="profile_picture" class="form-label">Profila bilde</label>--}}

{{--                    </div>--}}

                    <div class="form-row my-3">
                        <label for="telephone_number" class="form-label">Tālruņa numurs</label>
                        <div class="input-group">
                            <span class="input-group-text">+</span>
                            <input type="tel" id="telephone_number" class="form-control" name="telephone_number">
                        </div>
                        <small>Kopā ar valsts kodu</small>

                        @if ($errors->has('telephone_number'))
                            <div class="text-danger">{{ $errors->first('telephone_number') }}</div>
                        @endif
                    </div>

                    <div class="form-row">
                        <button type="submit" class="btn btn-primary mx-auto mx-2">Saglabāt izmaiņas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
