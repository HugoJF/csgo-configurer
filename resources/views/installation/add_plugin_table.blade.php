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
    @forelse($plugins->whereNotIn('id', $installation->plugins->pluck('id')) as $key=>$plugin)
        <tr>
            <td data-order="{{ $key }}">{{ $plugin->name }}</td>
            <td>{{ $plugin->description }}</td>
            <td>{{ $plugin->folder }}</td>
            <td>{{ $plugin->modified_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('plugin.show', $plugin) }}" class="btn btn-xs btn-success">View</a>
                {!! Form::open(['route' => ['installation.store-plugin', $installation, $plugin], 'method' => 'PATCH', 'style' => 'display:inline']) !!}
                <button class="btn btn-primary btn-xs"><strong>Add to installation</strong></button>
                {!! Form::close() !!}
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No plugins to add to installation</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>