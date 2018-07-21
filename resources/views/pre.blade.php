@extends('layout.app')

@section('content')
    @include('generics.pre', ['code' => $code, 'title' => $title ?? ''])
@endsection