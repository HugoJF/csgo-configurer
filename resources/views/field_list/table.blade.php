<div class="well">
    @forelse($fieldLists as $key => $fieldList)
        <div id="{{ str_slug($fieldList->name) }}" class="page-header">
            <h2><strong>{{ $prefix ?? '' }}{{ $fieldList->name }}</strong>
                <small>{{ $fieldList->description }}</small>
            </h2>
        </div>
        <h3>Fields</h3>
        <div>
            <a class="btn btn-sm btn-default" href="{{ route('field.create', $fieldList) }}">
                <span class="glyphicon glyphicon-plus-sign"></span> Add new field
            </a>
            <a class="btn btn-sm btn-primary" href="{{ route('field-list.edit', $fieldList) }}">
                <span class="glyphicon glyphicon-pencil"></span> Edit field
            </a>
            {!! Form::open(['route' => ['field-list.delete', $fieldList], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
            <button class="btn btn-sm btn-danger">
                <span class="glyphicon glyphicon-remove"></span> Delete field
            </button>
            {!! Form::close() !!}
        </div>
        <p></p>
        @include('field.table', ['fields' => $fieldList->fields])
        
        <h3>Sub Field Lists</h3>
        <div>
            <a class="btn btn-sm btn-default" href="{{ route('field-list.create', $fieldList) }}">
                <span class="glyphicon glyphicon-plus"></span> Add new field list
            </a>
        </div>
        <p></p>
        @include('field_list.table', ['fieldLists' => $fieldList->children->toFlatTree(), 'prefix' => ($prefix ?? '' ) . $fieldList->name . ' 🠢 '])
    @empty
        <tr>
            <td align="center" colspan="4"><strong>No field lists to display</strong></td>
        </tr>
    @endforelse
</div>