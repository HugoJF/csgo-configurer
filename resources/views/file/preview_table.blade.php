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
    @php
        $pluginSlugCache = null;
    @endphp
    @forelse($server->getRenderableInstallationFiles() as $key => $file)
        <tr>
            <td data-order="{{ $key }}"><code>{{ $file->path }}</code></td>
            <td>
                <label class="label label-{{ $file->isRenderable() ? 'success' : 'default' }}">{{ $file->isRenderable() ? 'True' : 'False' }}</label>
            </td>
            <td>{{ $file->owner_type }}</td>
            <td>{{ $file->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('server.preview-file', [$server, $file]) }}" class="btn btn-xs btn-primary">Preview</a>
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No files</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>