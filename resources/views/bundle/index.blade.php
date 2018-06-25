@extends('layout.app')

@section('content')
    <h1>Bundles</h1>
    <p>
        <a href="{{ route('bundle.create') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new bundles
        </a>
    </p>
    
    @include('bundle.table', $bundles)
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