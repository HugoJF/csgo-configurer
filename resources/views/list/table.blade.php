@forelse($lists as $key => $list)
    <div class="well">
        <div class="page-header">
            <h2>{{ $prefix ?? '' }}{{ $list->fieldList->name }}
                <small><a href="{{ $list->fieldList->routeShow() }}">{{ $list->key }}</a> - {{ $list->fieldList->description }}</small>
            </h2>
        </div>
        <a class="btn btn-sm btn-primary" href="{{ route('list.edit', $list) }}">
            <span class="glyphicon glyphicon-pencil"></span> Edit list
        </a>
        @if($list->active)
            <a class="btn btn-sm btn-danger" href="{{ route('list.deactivate', $list) }}">
                <span class="glyphicon glyphicon-off"></span> Deactivate list
            </a>
        @else
            <a class="btn btn-sm btn-success" href="{{ route('list.activate', $list) }}">
                <span class="glyphicon glyphicon-off"></span> Activate list
            </a>
        @endif
        {!! Form::open(['route' => ['list.delete', $list], 'method' => 'DELETE', 'style' => 'display:inline']) !!}
        <button class="btn btn-sm btn-danger">
            <span class="glyphicon glyphicon-remove"></span> Delete list
        </button>
        {!! Form::close() !!}
        
        <h3>Constants</h3>
        <a class="btn btn-sm btn-default" href="{{ route('constant.list.create', $list) }}">
            <span class="glyphicon glyphicon-plus-sign"></span> Add constant
        </a>
        <p></p>
        @include('constant.table_with_missing', ['constants' => $list->constants, 'fieldList' => $list->fieldList, 'owner' => $list])
        
        @if($list->fieldList->fieldLists()->count() > 0)
            <h3>Sub field-lists</h3>
            @include('field_list.add_list_table', ['fieldLists' => $list->fieldList->fieldLists, 'owner' => $list])
            <h3>Lists</h3>
            @include('list.table', ['lists' => $list->lists, 'prefix' => ($prefix ?? '' ) . $list->fieldList->name . ' 🠢 '])
        @endif
    </div>

@empty
    <div class="well well-sm">
        <tr>
            <td align="center" colspan="4"><strong>No lists to display</strong></td>
        </tr>
    </div>
@endforelse