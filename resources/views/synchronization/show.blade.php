@extends('layout.app')

@section('content')
    <h1>Synchronization logs #{{ $sync->id }}</h1>
    <pre>{{ $sync->logs->render() }}</pre>
@endsection