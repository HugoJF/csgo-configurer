<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Key</th>
        <th>Default</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($fields as $key => $field)
        <tr>
            <td style="white-space: nowrap" data-order="{{ $key }}">
                @if($field->required)
                    <strong>
                        <u>
                @endif
                {{ $field->name }}
                @if($field->required)
                        </u>
                    </strong>
                @endif
            </td>
            <td><pre style="white-space: pre-wrap">{!! $field->description !!}</pre></td>
            <td><code>{{ $field->key }}</code></td>
            <td><code style="white-space: nowrap">{{ $field->default }}</code></td>
            
            <td style="white-space: nowrap;">
                @if($field->required)
                    <a href="{{ route('field.optional', $field) }}" title="Make field optional" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-eye-close"></a>
                @else
                    <a href="{{ route('field.require', $field) }}" title="Make field required" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></a>
                @endif
                <a href="{{ route('field.edit', $field) }}" class="btn btn-xs btn-primary">Edit</a>
                {!! Form::open(['route' => ['field.delete', $field], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No fields</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>