@extends('layout.app')

@section('content')
    @include('generics.breadcrumbs', ['items' => [
        [
            'route' => 'home',
            'text' => 'Home'
        ],
        [
            'route' => 'server.index',
            'text' => 'Servers'
        ],
        [
            'route' => ['server.show', $server],
            'text' => $server->name
        ]
    ]])
    <div class="page-header">
        <h1>Server
            <small>{{ $server->name }}</small>
        </h1>
    </div>
    <h3>Server installation:
        
        @if(isset($server->installation->name))
            <a class="btn btn-success" href="{{ route('installation.show', $server->installation) }}">
                <span class="glyphicon glyphicon-road"></span>
                <strong>{{ $server->installation->name }}</strong>
            </a>
        @else
            <label class="label label-danger">No installation</label>
        @endif
    </h3>
    
    <h2>Server details</h2>
    <p>
        <a class="btn btn-primary" href="{{ route('server.edit', $server) }}">
            <span class="glyphicon glyphicon-pencil"></span> Edit
        </a>
        <a class="btn btn-primary" href="{{ route('server.render', $server) }}">
            <span class="glyphicon glyphicon glyphicon-fire"></span> Render
        </a>
        <a class="btn btn-primary" href="{{ route('server.sync', $server) }}">
            <span class="glyphicon glyphicon glyphicon-refresh"></span> Sync
        </a>
    </p>
    @include('server.table', ['servers' => [$server]])
    
    <h2>Server plugins</h2>
    @include('plugin.table', ['plugins' => $server->getPluginList()])
    
    <h2>Server configs</h2>
    <p>
        <a href="{{ route('config.create', ['server', $server]) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Create config for this server
        </a>
    </p>
    @include('config.table', ['configs' => $server->configs])
    
    <h2>Server variables</h2>
    @include('constant.table', ['constants' => $server->getConstants()['constants']])
    
    <h2>Server Config</h2>
    <pre>
        {{ json_encode($server->renderConfig()) }}
    </pre>
    
    <h2>Server rendered files</h2>
    @include('file.table', ['files' => $server->files])
@endsection