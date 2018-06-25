@extends('layout.app')

@section('content')
    <h1>Installation: {{ $installation->name }}</h1>
    
    <h4>{{ $installation->description }}</h4>
    
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