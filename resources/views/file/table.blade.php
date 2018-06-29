<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Path</th>
        <th>Renderable</th>
        <th>Owner Type</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($files as $key => $file)
        <tr>
            <td data-order="{{ $key }}">{{ $file->path }}</td>
            <td>
                <label class="label label-{{ $file->renderable ? 'success' : 'default' }}">{{ $file->renderable ? 'True' : 'False' }}</label>
            </td>
            <td>{{ $file->owner_type }}</td>
            <td>{{ $file->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                @if($file->owner_type == 'App\Server')
                    <a href="{{ route('file.server_show', [$file->owner_id, $file]) }}" class="btn btn-xs btn-success">View</a>
                @else
                    <a href="{{ route('file.show', [$file->owner_id, $file]) }}" class="btn btn-xs btn-success">View</a>
                @endif
                <a href="{{ route('file.edit', $file) }}" class="btn btn-xs btn-primary">Edit</a>
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No files</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>