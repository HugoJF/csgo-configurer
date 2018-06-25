@extends('layout.app')

@section('content')
    
    <h1>{{ $title }}</h1>

    {!! form_start($form) !!}

    {!! form_rest($form) !!}

    <div class="form-footer">
        <button type="submit" class="btn-success btn">{{ $submit_text }}</button>
    </div>

    {!! form_end($form) !!}
@endsection