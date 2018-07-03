<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
        <th>Config</th>
        <th>List</th>
        <th>Updated At</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($constants as $key => $constant)
        @if(is_array($constant))
            @continue
        @endif
        <tr>
            <td data-order="{{ $key }}">{{ $constant->key }}</td>
            
            @if($constant->list)
                <td>
                @foreach(json_decode($constant->value) as $k=>$v)
                    @if($v)
                            <label class="label label-primary">{{ $k }}: <u><span style="font-weight: 500">{{ $v }}</span></u></label>
                    @endif
                @endforeach
                </td>
            @else
                <td>{{ $constant->value }}</td>
            @endif
            <td><a href="{{ route('config.show', $constant->config) }}">{{ $constant->config->name }}</a></td>
            <td>{{ $constant->list ?? 'N/A' }}</td>
            <td>{{ $constant->updated_at->diffForHumans() }}</td>
            <td>{{ $constant->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('config.show', $constant->config) }}" class="btn btn-xs btn-success">View</a>
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="7"><strong>No constants to display</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>