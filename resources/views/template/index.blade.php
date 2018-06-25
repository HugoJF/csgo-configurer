@extends('layout.app')

@section('content')
    <h1>Templates</h1>
    <p><a href="{{ route('template.create') }}" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add new template</a></p>
    
    @include('template.table', $templates)
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