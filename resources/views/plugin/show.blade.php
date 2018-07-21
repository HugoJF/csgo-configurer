@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>Plugin
            <small>{{ $plugin->name }}</small>
        </h1>
    </div>
    <h2>Configs</h2>
    <p>
        <a class="btn btn-default" href="{{ route('config.create', ['plugin', $plugin->slug]) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Create config for this plugin
        </a>
    </p>
    
    @include('config.table', ['configs' => $plugin->configs])

    <h2>Fields</h2>
    <p>
        <a class="btn btn-default" href="{{ route('field.plugin.create', $plugin) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Create field
        </a>
    </p>
    @include('field.table', ['fields' => $plugin->fields])

    <h2>Field lists</h2>
    <p>
        <a class="btn btn-default" href="{{ route('field-list.plugin.create', $plugin) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Create field list
        </a>
    </p>
    @include('field_list.table', ['fieldLists' => $plugin->fieldLists])
    
    <h2>Files</h2>
    @include('file.table', ['files' => $plugin->files])
@endsection