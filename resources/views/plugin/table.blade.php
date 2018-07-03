<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Folder</th>
        <th>Modified At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($plugins as $key=>$plugin)
        <tr>
            <td data-order="{{ $key }}">{{ $plugin->name }}</td>
            <td>{{ $plugin->description }}</td>
            <td>{{ $plugin->folder }}</td>
            <td>{{ $plugin->modified_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('plugin.show', $plugin) }}" class="btn btn-sm btn-success">View</a>
            </td>
        </tr>
    @endforeach
    
    </tbody>
</table>