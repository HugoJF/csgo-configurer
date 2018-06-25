@extends('layout.app')

@section('content')
    <h1>Add plugin to installation {{ $installation->name }}</h1>
    
    @include('installation.add_plugin_table', ['installation' => $installation, 'plugins' => $plugins])
@endsection