@extends('layout.app')

@section('content')
    @if(isset($breadcrumbs))
        @include('generics.breadcrumbs', ['items' => $breadcrumbs])
    @endif
    <div class="page-header">
        <h1>{{ $title }}</h1>
    </div>
    
    
    {!! form_start($form) !!}
    
    {!! form_rest($form) !!}
    
    <div class="form-footer">
        <button type="submit" class="btn-success btn-block btn-lg btn">{{ $submit_text }}</button>
    </div>
    
    {!! form_end($form) !!}
@endsection

@if(isset($config))
    @push('scripts')
        <script>
            $(function () {
                $('#giantpotato').typeahead({
                    ajax: '{{ route('test', $config) }}',
                    valueField: 'key',
                    val: 'key',
                    displayField: 'name'
                });
            });
        </script>
    @endpush
@endif