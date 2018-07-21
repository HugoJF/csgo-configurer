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

<!-- TODO: Find an easy way to find the field list -->
@if(isset($config) && $config->owner_type == 'App\Plugin')
    @push('scripts')
        <script>
            $(function () {
                $('#key').typeahead({
                    ajax: '{{ route('plugin.fields', $config->owner) }}',
                    valueField: 'key',
                    displayField: 'display'
                });
            });
        </script>
    @endpush
@endif

@if(isset($config))
    @push('scripts')
        <script>

            $(function () {
                $('#value').typeahead({
                    ajax: '{{ route('config.values', $config) }}',
                    valueField: 'key',
                    displayField: 'display',
                    ajaxLookup: function () {

                        var query = $.trim(this.$element.val());

                        if (query === this.query) {
                            return this;
                        }

                        // Query changed
                        this.query = query.match(/{[a-zA-Z0-9.-_% ]*}?$/);

                        // Check if match found something
                        if (this.query == null) {
                            return this;
                        }

                        // Get first match (should be unique)
                        this.query = this.query[0];

                        console.log("Matching " + this.query + " from " + this.$element.val());

                        // Cancel last timer if set
                        if (this.ajax.timerId) {
                            clearTimeout(this.ajax.timerId);
                            this.ajax.timerId = null;
                        }

                        if (!query || query.length < this.ajax.triggerLength) {
                            // cancel the ajax callback if in progress
                            if (this.ajax.xhr) {
                                this.ajax.xhr.abort();
                                this.ajax.xhr = null;
                                this.ajaxToggleLoadClass(false);
                            }

                            return this.shown ? this.hide() : this;
                        }

                        function execute() {
                            this.ajaxToggleLoadClass(true);

                            // Cancel last call if already in progress
                            if (this.ajax.xhr)
                                this.ajax.xhr.abort();

                            var params = this.ajax.preDispatch ? this.ajax.preDispatch(query) : {
                                query: query
                            };
                            this.ajax.xhr = $.ajax({
                                url: this.ajax.url,
                                data: params,
                                success: $.proxy(this.ajaxSource, this),
                                type: this.ajax.method || 'get',
                                dataType: 'json',
                                headers: this.ajax.headers || {}
                            });
                            this.ajax.timerId = null;
                        }

                        // Query is good to send, set a timer
                        this.ajax.timerId = setTimeout($.proxy(execute, this), this.ajax.timeout);

                        return this;
                    },
                    select: function () {
                        var $selectedItem = this.$menu.find('.active');
                        if ($selectedItem.length) {
                            var value = $selectedItem.attr('data-value');
                            var text = this.$menu.find('.active a').text();


                            value = this.$element.val().replace(/{[a-zA-Z0-9.-_% ]*}?$/, value);

                            this.$element
                                .val(this.updater(value))
                                .change();

                            if (this.options.onSelect) {
                                this.options.onSelect({
                                    value: value,
                                    text: text
                                });
                            }
                        }
                        return this.hide();
                    },

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