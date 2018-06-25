<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Config</th>
        <th>Folder</th>
        <th>Modified At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($installation->templates as $key => $template)
        <tr>
            <td data-order="{{ $key }}"><a href="{{ route('template.show', $template) }}">{{ $template->name }}</a></td>
            <td>{{ $template->description }}</td>
            <td>
                @if($template->pivot->selection)
                    <a href="{{ route('bundle.show', $template->pivot->selection) }}">{{ $template->pivot->selection->name }}</a>
                @else
                    <a class="btn btn-success btn-xs" href="{{ route('installation.create-selection', [ $installation, $template ]) }}">Select bundle</a>
                @endif
            </td>
            <td>{{ $template->folder }}</td>
            <td>{{ $template->modified_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('template.show', $template) }}" class="btn btn-xs btn-success">View</a>
                {!! Form::open(['route' => ['installation.remove-template', $installation, $template], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            
            </td>
        </tr>
    @endforeach
    
    </tbody>
</table>