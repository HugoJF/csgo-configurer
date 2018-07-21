@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>Add plugin to installation
            <small>{{ $installation->name }}</small>
        </h1>
    </div>
    
    @include('installation.add_plugin_table', ['installation' => $installation, 'plugins' => $plugins])
@endsection