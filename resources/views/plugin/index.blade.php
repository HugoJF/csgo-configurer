@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>Plugins</h1>
    </div>
    <p>
        <a href="{{ route('plugin.create') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new plugin
        </a>
        <a href="{{ route('plugin.sync') }}" id="generate" type="submit" name="generate" class="btn btn-primary">
            <span class="glyphicon glyphicon-check"></span> Sync plugins
        </a>
    </p>
    
    @include('plugin.table', $plugins)
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