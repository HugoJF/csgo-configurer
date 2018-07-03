@extends('layout.app')

@section('content')
    @include('generics.breadcrumbs', ['items' => [
        [
            'route' => 'home',
            'text' => 'Home'
        ]
    ]])
    <div class="page-header">
        <h1>Home</h1>
    </div>
    @if(Auth::check())
        <h1>sup {{ Auth::user()->username }}</h1>
    @else
        <h1>Who?</h1>
    @endif
@endsection