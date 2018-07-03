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
            <td data-order="{{ $key }}">{{ $field->name }}</td>
            <td>{{ $field->description }}</td>
            <td><code>{{ $field->key }}</code></td>
            <td>{{ $field->default }}</td>
            
            <td style="white-space: nowrap;">
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