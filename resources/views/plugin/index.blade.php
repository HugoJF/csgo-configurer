@extends('layout.app')

@section('content')
    <h1>Plugins</h1>
    <p><a href="{{ route('plugin.create') }}" id="generate" type="submit" name="generate" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Add new plugin</a></p>
    
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