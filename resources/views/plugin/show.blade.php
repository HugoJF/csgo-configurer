@extends('layout.app')

@section('content')
    <h1>Plugin: {{ $plugin->name }}</h1>
    
    <h2>Configs</h2>
    <p>
        <a class="btn btn-default" href="{{ route('config.create', ['plugin', $plugin]) }}">
            <span class="glyphicon glyphicon-pencil"></span> Create config for this plugin
        </a>
    </p>
    @include('config.table', ['configs' => $plugin->configs])
    
    <h2>Files</h2>
    @include('file.table', ['files' => $plugin->files])
@endsection