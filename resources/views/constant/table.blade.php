<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
        <th>Updated At</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($constants as $key => $constant)
        <tr {{ $constant->active ? '' : 'class=inactive' }}>
            <td data-order="{{ $key }}">{{ $constant->key }}</td>
            <td>{{ $constant->value }}</td>
            <td>{{ $constant->updated_at->diffForHumans() }}</td>
            <td>{{ $constant->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                @if($constant->active)
                    <a title="Deactivate constant" href="{{ route('constant.deactivate', $constant) }}" class="btn btn-xs btn-danger">
                        <span class="glyphicon glyphicon-off"></span>
                    </a>
                @else
                    <a title="Activate constant" href="{{ route('constant.activate', $constant) }}" class="btn btn-xs btn-success">
                        <span class="glyphicon glyphicon-off"></span>
                    </a>
                @endif
                <a href="{{ route('constant.edit', $constant) }}" class="btn btn-xs btn-primary">Edit</a>
                {!! Form::open(['route' => ['constant.delete', $constant], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No constants to display</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>