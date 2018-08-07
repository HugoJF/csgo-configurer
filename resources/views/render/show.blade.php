@extends('layout.app')

@section('content')
    <h1>Render logs #{{ $render->id }}</h1>
    <pre>{{ $render->logs->render() }}</pre>
@endsection