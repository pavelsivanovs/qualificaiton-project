@extends('layouts.index')

@section('title', 'Pieteikties sistēmā')

@section('content')
    <div class="container d-flex flex-column justify-content-center">
        <div class="heading">
            <h2 class="h2">Sveicināti!</h2>
            <h4>{{ config('app.name') }}</h4>
        </div>
        <div class="login m-2">
            <div class="card p-4 w-50 container">
                <form method="POST" action="{{ route('loginUser') }}" class="needs-validation">
                    @csrf
                    <div class="form-row my-4">
                        <label for="email" class="form-label">E-pasts</label>
                        <input type="email" id="email" class="form-control" name="email">

                        @if ($errors->has('email'))
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-row my-4">
                        <label for="password" class="form-label">Parole</label>
                        <input type="password" id="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <div class="text-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-row">
                        <button type="submit" class="btn btn-primary mx-auto mx-2">Pieteikties</button>
                        <a class="text-center mx-2" href="{{ route('showRegister') }}">
                            <button type="button" class="btn btn-secondary">Reģistrēties</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
