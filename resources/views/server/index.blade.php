@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>Servers</h1>
    </div>
    <p>
        <a href="{{ route('server.create') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new server
        </a>
        <a href="{{ route('server.render-all') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon glyphicon-fire"></span> Render all
        </a>
        <a href="{{ route('server.sync-all') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon glyphicon-refresh"></span> Sync all
        </a>
    </p>
    
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