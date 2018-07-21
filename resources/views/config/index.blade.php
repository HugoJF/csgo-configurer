@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>Configs</h1>
    </div>
    
    <p>
        <a href="{{ route('config.create') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new configs
        </a>
    </p>
    
    @include('config.table', $configs)
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