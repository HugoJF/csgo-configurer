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
    ]])
    <div class="page-header">
        <h1>Installations</h1>
    </div>
    <p>
        <a href="{{ route('installation.create') }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new installation
        </a>
    </p>
    
    <table id="datatables" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Updated At</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($installations as $key => $installation)
            <tr>
                <td data-order="{{ $key }}"><a href="{{ route('installation.show', $installation) }}">{{ $installation->name }}</a></td>
                
                <td>{{ $installation->description }}</td>
                <td>{{ $installation->updated_at->diffForHumans() }}</td>
                <td>{{ $installation->created_at->diffForHumans() }}</td>
                
                <td style="white-space: nowrap;">
                    <a href="{{ route('installation.show', $installation) }}" class="btn btn-xs btn-success">View</a>
                    <a href="{{ route('installation.edit', $installation) }}" class="btn btn-xs btn-primary">Edit</a>
                    {!! Form::open(['route' => ['installation.delete', $installation], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                    <button class="btn btn-xs btn-danger">Delete</button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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