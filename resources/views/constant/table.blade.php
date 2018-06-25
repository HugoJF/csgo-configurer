<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
        <th>Bundle</th>
        <th>Updated At</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($constants as $key => $constant)
        <tr>
            <td data-order="{{ $key }}">{{ $constant->key }}</td>
            
            <td>{{ $constant->value }}</td>
            <td><a href="{{ route('bundle.show', $constant->bundle) }}">{{ $constant->bundle->name }}</a></td>
            <td>{{ $constant->updated_at->diffForHumans() }}</td>
            <td>{{ $constant->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('bundle.show', $constant) }}" class="btn btn-xs btn-success">View</a>
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="6"><strong>No constants to display</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>