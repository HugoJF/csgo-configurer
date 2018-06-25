@extends('layout.app')

@section('content')
    <h1>{{ $server->name }} - ({{ $server->ip }}:{{ $server->port }})</h1>
    
    <h3>Server installation name:
        
        @if(isset($server->installation->name))
            <a class="btn btn-success" href="{{ route('installation.show', $server->installation) }}">
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
        <a class="btn btn-success" href="{{ route('server.render', $server) }}">
            <span class="glyphicon glyphicon glyphicon-fire"></span> Render
        </a>
    </p>
    @include('server.table', ['servers' => [$server]])
    
    <h2>Server bundles</h2>
    <p>
        <a href="{{ route('bundle.create', ['server', $server]) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Create bundle for this server
        </a>
    </p>
    @include('bundle.table', ['bundles' => $server->bundles])
    
    <h2>Server variables</h2>
    @include('constant.table', ['constants' => $server->getConstants()['constants']])
@endsection