<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
        <th>Updated At</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($constants as $key => $constant)
        @php
            $custom = $fieldList->fields->keyBy('key')->get($constant->key) == null;
        @endphp
        <tr {{ $custom ? 'class=warning' : '' }}>
            <td title="This is a custom constant" data-order="{{ $key }}">{!! $custom ? '<i>*' : '' !!}{{ $constant->key }}{!! $custom ? '</i  >' : '' !!}</td>
            <td>{{ $constant->value }}</td>
            <td>{{ $constant->updated_at->diffForHumans() }}</td>
            <td>{{ $constant->created_at->diffForHumans() }}</td>
            
            <td style="white-space: nowrap;">
                <a href="{{ route('constant.edit', $constant) }}" class="btn btn-xs btn-primary">Edit</a>
                {!! Form::open(['route' => ['constant.delete', $constant], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="5"><strong>No constants to display</strong></td>
        </tr>
    @endforelse
    
    @foreach ($fieldList->fields as $field)
        @php
            $constants = $constants->keyBy('key');
        @endphp
        @if(!$constants->get($field->key))
            <!-- Add constants -->
            <tr class="danger">
                <td align="center" colspan="4">Missing constant <strong>{{ $field->key }}</strong> from field-list <strong>{{ $fieldList->name }}</strong></td>
                <td style="white-space: nowrap;">
                    <a href="{{ route('constant.list.create', ['list' => $owner, 'field_list' => null, 'key' => $field->key]) }}" class="btn btn-xs btn-success">Create constant <strong>{{ $field->key  }}</strong></a>
                </td>
            </tr>
        @endif
    @endforeach
    
    </tbody>
</table>