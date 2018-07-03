@extends('layout.app')

@section('content')
    @include('generics.breadcrumbs', ['items' => [
        [
            'route' => 'home',
            'text' => 'Home'
        ],
        [
            'route' => 'installation.index',
            'text' => 'Installations'
        ],
        [
            'route' => ['installation.show', $installation],
            'text' => $installation->name
        ]
    ]])
    <div class="page-header">
        <h1>Installation
            <small>{{ $installation->name }}</small>
            <h4>{{ $installation->description }}</h4>
        </h1>
    </div>
    
    
    <h2>Plugins</h2>
    <p>
        <a class="btn btn-default" href="{{ route('installation.add-plugin', $installation) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Add Plugin to installation
        </a>
    </p>
    
    @include('installation.plugin_table', $installation)
    
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#datatables').DataTable({
                "iDisplayLength": 50
            });
        });
    </script>
@endpush