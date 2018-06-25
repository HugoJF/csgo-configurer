@extends('layout.app')

@section('content')
    <h1>{{ $file->path }}</h1>
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