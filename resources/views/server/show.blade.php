@extends('layout.app')

@section('content')
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
            <a href="{{ route('server.edit', $server) }}">
                <label class="label label-danger">No installation selected</label>
            </a>
        @endif
    </h3>
    
    <h2 id="server-details">Server details</h2>
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
    
    <h2 id="server-plugins">Server plugins</h2>
    @include('plugin.table', ['plugins' => $server->getPluginList()])
    
    <h2 id="server-configs">Server configs</h2>
    <p>
        <a href="{{ route('config.create', ['server', $server]) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Create config for this server
        </a>
    </p>
    @include('config.table', ['configs' => $server->configs])
    
    <h2 id="server-render-configs-order">Server render config order</h2>
    <p>
        <a href="{{ route('config.create', ['server', $server]) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Create config for this server
        </a>
    </p>
    @include('config.table', ['configs' => $server->getConfigs()])

    <h2 id="server-installation-files-preview">Server installation files preview</h2>
    @include('file.preview_table', ['server' => $server])

    <h2 id="server-renders">Server renders</h2>
    @include('render.table', ['renders' => $server->renders])

    <h2 id="server-renders">Server synchronizations</h2>
    @include('synchronization.table', ['syncs' => $server->syncs])

    <h2 id="server-rendered-files">Server rendered files</h2>
    @include('file.table', ['files' => $server->files()->rendered()->get()])
    
    <h2 id="server-backup-files">Server backup files</h2>
    @include('file.table', ['files' => $server->files()->backup()->get()])
    
    <h2 id="server-synced-files">Server synced files</h2>
    @include('file.table', ['files' => $server->files()->synced()->get()])
    
    <h2 id="server-render-config">Server render config</h2>
    @include('generics.pre', ['code' => $server->renderConfig()])
@endsection

@push('sidebar')
    <nav id="myScrollspy">
        <ul class="nav nav-pills nav-stacked" data-offset-top="500">
            <li class="active"><a href="#home"><strong>Server details</strong></a></li>
            <li><a href="#server-plugins">Server plugins</a></li>
            <li><a href="#server-configs">Server configs</a></li>
            <li><a href="#server-render-configs-order">Server render configs order</a></li>
            <li><a href="#server-installation-files-preview">Server renders</a></li>
            <li><a href="#server-renders">Server installation files preview</a></li>
            <li><a href="#server-rendered-files">Server rendered files</a></li>
            <li><a href="#server-backup-files">Server backup files</a></li>
            <li><a href="#server-synced-files">Server synced files</a></li>
            <li><a href="#server-render-config">Server render config</a></li>
        </ul>
    </nav>
@endpush