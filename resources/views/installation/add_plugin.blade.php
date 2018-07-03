@extends('layout.app')

@section('content')
    @include('generics.breadcrumbs', ['items' => [
        [
            'route' => 'home',
            'text' => 'Home',
        ],
        [
            'route' => 'installation.index',
            'text' => 'Installations',
        ],
        [
            'route' => ['installation.show', $installation],
            'text' => $installation->name,
        ],
        [
            'route' => ['installation.show', $installation],
            'text' => 'Plugins',
        ],
        [
            'url' => url()->current(),
            'text' => 'Adding plugin to installation',
        ]
    ]])
    <div class="page-header">
        <h1>Add plugin to installation
            <small>{{ $installation->name }}</small>
        </h1>
    </div>
    
    @include('installation.add_plugin_table', ['installation' => $installation, 'plugins' => $plugins])
@endsection