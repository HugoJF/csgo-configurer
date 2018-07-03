<div class="well well-sm">
    @forelse($fieldLists as $key => $fieldList)
        <h3>{{ $fieldList->name }}</h3>
        <p>{{ $fieldList->description }}</p>
        <div class="">
            <a class="btn btn-default" href="{{ route('field.create', $fieldList) }}">
                <span class="glyphicon glyphicon-pencil"></span> Add new field
            </a>
            <a class="btn btn-primary" href="{{ route('field-list.edit', $fieldList) }}">
                <span class="glyphicon glyphicon-pencil"></span> Edit
            </a>
            {!! Form::open(['route' => ['field-list.delete', $fieldList], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
            <button class="btn btn-danger">Delete</button>
            {!! Form::close() !!}
        </div>
        <p></p>
        @include('field.table', ['fields' => $fieldList->fields])
    @empty
        <tr>
            <td align="center" colspan="4"><strong>No field lists to display</strong></td>
        </tr>
    @endforelse
</div>