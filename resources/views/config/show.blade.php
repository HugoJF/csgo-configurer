@extends('layout.app')

@section('content')
    <h1>Config: {{ $config->name }}</h1>

    <p>
        <a href="{{ route('constant.create', $config) }}" id="generate" type="submit" name="generate" class="btn btn-default">
            <span class="glyphicon glyphicon-plus-sign"></span> Add new constant
        </a>
    </p>

    @include('constant.table', ['constants' => $config->constants])
@endsection