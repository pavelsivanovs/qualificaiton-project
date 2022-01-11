@extends('layouts.index')

@section('title', 'Reģistrēties sistēmā')

@section('content')
    <div class="container d-flex flex-column justify-content-center">
        <div class="login m-2">
            <div class="card p-4 w-50 container">
                <h4 class="h4">Reģistrācija</h4>
                <form method="POST" action="{{ route('registerUser') }}" class="needs-validation">
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

                    <div class="form-row my-3">
                        <label for="profile_picture" class="form-label">Profila bilde</label>

                    </div>

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
                        <button type="submit" class="btn btn-primary mx-auto mx-2">Reģistrēties</button>
                        <a class="text-center mx-2" href="{{ url()->previous() }}">
                            <button type="button" class="btn btn-secondary">< Atpakaļ</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
