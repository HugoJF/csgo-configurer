@extends('layout.app')

@section('content')
    @include('generics.breadcrumbs', ['items' => [
        [
            'route' => 'home',
            'text' => 'Home'
        ],
        [
            'route' => 'config.index',
            'text' => 'Configs'
        ],
        [
            'route' => ['config.show', $config],
            'text' => $config->name
        ]
    ]])
    <div class="page-header">
        <h1>Config
            <small>{{ $config->name }}</small>
        </h1>
    </div>
    
    <h2>Constants</h2>
    <p>
        <a href="{{ route('constant.create', $config) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new constant
        </a>
    </p>
    @include('constant.table', ['constants' => $config->constants])
    
    <h2>Field lists</h2>
        @include('field_list.add_constant_table', ['fieldLists' => $config->fieldLists(), 'config' => $config])

@endsection