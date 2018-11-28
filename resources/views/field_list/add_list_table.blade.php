<table id="datatables" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Fields</th>
        <th>Plugin</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($fieldLists as $key => $fieldList)
        <tr>
            <td data-order="{{ $key }}">{{ $fieldList->name }}</td>
            
            <td>
                @foreach($fieldList->fields as $f)
                    <span title="<strong><u>{{ $f->key }}</u></strong>: {{ $f->description }}" data-html="true" data-toggle="tooltip" data-placement="top" class="label label-default">{{ $f->name }}</span>
                @endforeach
            </td>
            
            <td><a href="{{ $fieldList->parent->routeShow()  }}">{{ $fieldList->parent->name }}</a></td>
            <td style="white-space: nowrap;">
                <a href="{{ route('list.create', [$owner, $fieldList]) }}" class="btn btn-xs btn-success">Instantiate</a>
                <a href="{{ route('field-list.edit', $fieldList) }}" class="btn btn-xs btn-primary">Edit</a>
                {!! Form::open(['route' => ['field-list.delete', $fieldList], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
                <button class="btn btn-xs btn-danger">Delete</button>
                {!! Form::close() !!}
            </td>
        </tr>
    @empty
        <tr>
            <td align="center" colspan="4"><strong>No field lists to display</strong></td>
        </tr>
    @endforelse
    
    </tbody>
</table>

@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endpush