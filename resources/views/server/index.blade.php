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
    ]])
    <div class="page-header">
        <h1>Servers</h1>
    </div>
    <p><a href="{{ route('server.create') }}" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add new server</a></p>
    
    @include('server.table', $servers)
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#datatables').DataTable({
                "iDisplayLength": 50
            });
        });
    </script>
@endpush