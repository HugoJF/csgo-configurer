@extends('layout.app')

@section('content')
    <div id="plugin" class="page-header">
        <h1>Plugin
            <small>{{ $plugin->name }}</small>
        </h1>
    </div>
    <h2 id="configs">Configs</h2>
    <p>
        <a class="btn btn-default" href="{{ route('config.create', ['plugin', $plugin->slug]) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Create config for this plugin
        </a>
    </p>
    
    @include('config.table', ['configs' => $plugin->configs])

    <h2 id="fields">Fields</h2>
    <p>
        <a class="btn btn-default" href="{{ route('field.create', $plugin->data) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Create field
        </a>
    </p>
    @include('field.table', ['fields' => $plugin->data->fields])

    <h2 id="field-lists">Field lists</h2>
    <p>
        <a class="btn btn-default" href="{{ route('field-list.create', $plugin->data) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Create field list
        </a>
    </p>
    @include('field_list.table', ['fieldLists' => $plugin->data->children->toFlatTree()])
    
    <h2 id="files">Files</h2>
    @include('file.table', ['files' => $plugin->files])
@endsection

@push('sidebar')
    <nav id="myScrollspy">
        <ul class="nav nav-pills nav-stacked" data-offset-top="500">
            <li class="active"><a href="#home"><strong>Plugin details</strong></a></li>
            <li><a href="#configs">Configs</a></li>
            <li><a href="#fields">Fields</a></li>
            <li><a href="#field-lists">Field-Lists</a></li>
            @include('field_list.scrollspy', ['fieldLists' => $plugin->data->children])
            <li><a href="#files">Files</a></li>
        </ul>
    </nav>
@endpush