@extends('layout.app')

@section('content')
    <div class="page-header">
        <h1>File
            <small>{{ $file->path }}</small>
        </h1>
    </div>
    
    <p>
        <a class="btn btn-primary" href="{{ route('file.edit', $file) }}">
            <span class="glyphicon glyphicon-pencil"></span> Edit
        </a>
    </p>
    @include('file.table', ['files' => [$file]])
    
    @if($file->owner_type == 'App\Plugin')
        <h2>Field Lists</h2>
        <p>
            <a class="btn btn-default" href="{{ route('field-list.plugin.create', $file->owner) }}">
                <span class="glyphicon glyphicon-pencil"></span> Create field list
            </a>
        </p>
        @include('field_list.table', ['fieldLists' => $file->fieldLists])
    @endif
    
    @if($file->renderable)
        <pre>{{ $content ?? '' }}</pre>
    @endif

@endsection