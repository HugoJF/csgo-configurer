@extends('layout.app')

@section('content')
    <h1>Installation: {{ $installation->name }}</h1>
    
    <h4>{{ $installation->description }}</h4>
    
    <h2>Templates</h2>
    <p>
        <a class="btn btn-default" href="{{ route('installation.add-template', $installation) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Add Template to installation
        </a>
    </p>
    
    @include('installation.template_table', $installation)
    
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