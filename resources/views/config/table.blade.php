    <table id="datatables" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Priority</th>
            <th>Owner</th>
            <th>Updated At</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($configs as $key => $config)
            <tr>
                <td data-order="{{ $key }}"><a href="{{ route('config.show', $config) }}">{{ $config->name }}</a></td>
                
                <td>{{ $config->slug }}</td>
                <td>{{ $config->priority }}</td>
                <td>{{ $config->owner_type }}</td>
                <td>{{ $config->updated_at->diffForHumans() }}</td>
                <td>{{ $config->created_at->diffForHumans() }}</td>
                
                <td style="white-space: nowrap;">
                    <a href="{{ route('config.show', $config) }}" class="btn btn-xs btn-success">View</a>
                    <a href="{{ route('config.edit', $config) }}" class="btn btn-xs btn-primary">Edit</a>
                    {!! Form::open(['route' => ['config.delete', $config], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                    <button class="btn btn-xs btn-danger">Delete</button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @empty
            <tr>
                <td align="center" colspan="7"><strong>No configs to display</strong></td>
            </tr>
        @endforelse
        
        </tbody>
    </table>