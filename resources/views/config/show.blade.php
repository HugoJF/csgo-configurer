@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>Config
            <small>{{ $config->name }}</small>
        </h1>
    </div>
    
    <h2>Constants</h2>
    <p>
        <a href="{{ route('constant.config.create', $config) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new constant
        </a>
    </p>
    @include('constant.table', ['constants' => $config->constants])

    <h2>Field lists</h2>
    @include('field_list.add_list_table', ['fieldLists' => $config->fieldLists(), 'owner' => $config])

    <h2>Lists</h2>
    @include('list.table', ['lists' => $config->lists])
    
@endsection