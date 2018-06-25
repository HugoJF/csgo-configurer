    <table id="datatables" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Owner</th>
            <th>Updated At</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($bundles as $key => $bundle)
            <tr>
                <td data-order="{{ $key }}"><a href="{{ route('bundle.show', $bundle) }}">{{ $bundle->name }}</a></td>
                
                <td>{{ $bundle->slug }}</td>
                <td>{{ $bundle->owner_type }}</td>
                <td>{{ $bundle->updated_at->diffForHumans() }}</td>
                <td>{{ $bundle->created_at->diffForHumans() }}</td>
                
                <td style="white-space: nowrap;">
                    <a href="{{ route('bundle.show', $bundle) }}" class="btn btn-xs btn-success">View</a>
                    <a href="{{ route('bundle.edit', $bundle) }}" class="btn btn-xs btn-primary">Edit</a>
                    {!! Form::open(['route' => ['bundle.delete', $bundle], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                    <button class="btn btn-xs btn-danger">Delete</button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @empty
            <tr>
                <td align="center" colspan="6"><strong>No bundles to display</strong></td>
            </tr>
        @endforelse
        
        </tbody>
    </table>