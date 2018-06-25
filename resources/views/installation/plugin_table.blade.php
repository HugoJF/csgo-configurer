<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Config</th>
        <th>Priority</th>
        <th>Folder</th>
        <th>Modified At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($installation->plugins as $key => $plugin)
        <tr>
            <td data-order="{{ $key }}"><a href="{{ route('plugin.show', $plugin) }}">{{ $plugin->name }}</a></td>
            <td>{{ $plugin->description }}</td>
            <td>
                @if($plugin->pivot->selection)
                    <a href="{{ route('config.show', $plugin->pivot->selection) }}">{{ $plugin->pivot->selection->name }}</a>
                @else
                    <a class="btn btn-success btn-xs" href="{{ route('installation.create-selection', [ $installation, $plugin ]) }}">Select config</a>
                @endif
            </td>
            <td>{{ $plugin->pivot->priority ?? 'N/A' }}</td>
            <td>{{ $plugin->folder }}</td>
            <td>{{ $plugin->modified_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('plugin.show', $plugin) }}" class="btn btn-xs btn-success">View</a>
                <a class="btn btn-primary btn-xs" href="{{ route('installation.create-selection', [ $installation, $plugin]) }}">Edit</a>
                {!! Form::open(['route' => ['installation.remove-plugin', $installation, $plugin], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            
            </td>
        </tr>
    @endforeach
    
    </tbody>
</table>