@extends('layouts.index')

@section('title', 'Jauns uzdevums')

@section('content')
    <div class="container">
{{--        <h2 class="h2 fw-bold">Jauns uzdevums</h2>--}}

{{--        <form method="POST" action="{{ route('createTask') }}">--}}
{{--            @csrf--}}
{{--            <div class="card">--}}
{{--            </div>--}}
{{--        </form>--}}
        <x-under-construction></x-under-construction>
    </div>
@endsection
