@extends('layout.app')

@section('content')
    <h1>Running bitches</h1>
    @if(Auth::check())
        <h1>sup {{ Auth::user()->username }}</h1>
    @else
        <h1>Who?</h1>
    @endif
@endsection