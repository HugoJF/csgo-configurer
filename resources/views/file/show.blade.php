@extends('layout.app')

@section('content')
    @if(isset($breadcrumbs))
        @include('generics.breadcrumbs', ['items' => $breadcrumbs])
    @endif

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
    @if($file->renderable)
        <pre>{{ $content ?? '' }}</pre>
    @endif

@endsection