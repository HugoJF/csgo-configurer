@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>#<span title="Config priority">{{ $config->priority }}</span> Config
            <small>{{ $config->name }}</small>
        </h1>
        <a href="{{ Request::url() }}" class="btn btn-primary">Hide missing fields</a>
        <a href="{{ Request::fullUrlWithQuery(['show-missing' => 'optional']) }}" class="btn btn-primary">Show missing optional fields</a>
        <a href="{{ Request::fullUrlWithQuery(['show-missing' => 'required']) }}" class="btn btn-primary">Show missing required fields</a>
    </div>
    
    <h2 id="constants">Constants</h2>
    <p>
        <a href="{{ route('constant.create', $config->data) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new constant
        </a>
    </p>
    @include('constant.table', ['constants' => $config->data->constants])

    <h2 id="field-lists">Field lists</h2>
    @include('field_list.add_list_table', ['fieldLists' => $config->getFieldLists(), 'owner' => $config->data])
    
    <h2 id="lists">Lists</h2>
    @include('list.table', ['lists' => $config->data->descendants()->with(['fieldList', 'fieldList.children', 'fieldList.children.fields', 'fieldList.children.parent',  'fieldList.fields', 'constants'])->get()->toTree()])
    
@endsection

@push('sidebar')
    <nav id="myScrollspy">
        <ul class="nav nav-pills nav-stacked" data-offset-top="500">
            <li class="active"><a href="#home"><strong>Config details</strong></a></li>
            <li><a href="#constants">Constants</a></li>
            <li><a href="#field-lists">Field Lists</a></li>
            <li><a href="#lists">Lists</a></li>
        </ul>
    </nav>
@endpush