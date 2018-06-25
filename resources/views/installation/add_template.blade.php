@extends('layout.app')

@section('content')
    <h1>Add template to installation {{ $installation->name }}</h1>
    <ul>
        @forelse($templates as $template)
            @if(!$installation->templates->pluck('id')->contains($template->id))
                {!! Form::open(['route' => ['installation.store-template', $installation, $template], 'method' => 'PATCH', 'style' => 'display:inline']) !!}
                <p>{{ $template->name }}
                    <button class="btn btn-success">@lang('messages.sync')</button>
                </p>
                {!! Form::close() !!}
            @endif
        @empty
            <h3>There are not templates to select to install</h3>
        @endforelse
    </ul>
@endsection