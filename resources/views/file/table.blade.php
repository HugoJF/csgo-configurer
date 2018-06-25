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
    @foreach($files as $key => $file)
        <tr>
            <td data-order="{{ $key }}">{{ $file->path }}</td>
            <td>
                <label class="label label-{{ $file->renderable ? 'success' : 'default' }}">{{ $file->renderable ? 'True' : 'False' }}</label>
            </td>
            <td>{{ $file->owner_type }}</td>
            <td>{{ $file->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('file.show', [$file->owner, $file]) }}" class="btn btn-xs btn-success">View</a>
                <a href="{{ route('file.edit', $file) }}" class="btn btn-xs btn-primary">Edit</a>
            </td>
        </tr>
    @endforeach
    
    </tbody>
</table>