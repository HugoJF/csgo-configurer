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
    @forelse($files as $key => $file)
        <tr>
            <td data-order="{{ $key }}"><code>{{ $file->path }}</code></td>
            <td>
                <label class="label label-{{ $file->isRenderable() ? 'success' : 'default' }}">{{ $file->isRenderable() ? 'True' : 'False' }}</label>
            </td>
            <td>{{ $file->owner_type }}</td>
            <td>{{ $file->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                @if($file->owner_type == 'App\Server')
                    <a href="{{ route('file.server_show', [$file->owner_id, $file]) }}" class="btn btn-xs btn-success">View</a>
                @else
                    @php
                        if(!$pluginSlugCache) {
                            $pluginSlugCache = $file->owner->slug;
                        }
                    @endphp
                    <a href="{{ route('file.show', [$pluginSlugCache, $file]) }}" class="btn btn-xs btn-success">View</a>
                @endif
                
                @if($file->isRenderable())
                    {!! Form::open(['route' => ['file.make-static', $file], 'method' => 'PATCH', 'style' => 'display:inline']) !!}
                    <button class="btn btn-xs btn-warning">Make Static</button>
                    {!! Form::close() !!}
                @else
                    {!! Form::open(['route' => ['file.make-renderable', $file], 'method' => 'PATCH', 'style' => 'display:inline']) !!}
                    <button class="btn btn-xs btn-success">Make Renderable</button>
                    {!! Form::close() !!}
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