@extends('layout.app')

@section('content')
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
                $('#key').typeahead({
                    ajax: '{{ route('plugin.fields', $config->owner) }}',
                    valueField: 'key',
                    displayField: 'display'
                });
            });
            
            $(function () {
                $('#value').typeahead({
                    ajax: '{{ route('config.values', $config) }}',
                    valueField: 'key',
                    displayField: 'display'
                });
            });
        </script>
    @endpush
@endif

@push('scripts')
    <script>
        $('select').selectpicker();
    </script>
@endpush