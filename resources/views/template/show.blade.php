@extends('layout.app')

@section('content')
    <h1>Template: {{ $template->name }}</h1>
    
    <h2>Bundles</h2>
    <p>
        <a class="btn btn-default" href="{{ route('bundle.create', ['template', $template]) }}">
            <span class="glyphicon glyphicon-pencil"></span> Create bundle for this template
        </a>
    </p>
    @include('bundle.table', ['bundles' => $template->bundles])
    
    <h2>Files</h2>
    @include('file.table', ['files' => $template->files])
@endsection