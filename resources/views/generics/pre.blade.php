<h1>{{ $title ?? ''}}</h1>
<pre class="prettyprint linenums">{{( $encode ?? true) ? json_encode($code) : $code }}</pre>

@push('scripts')
    <script>
        var html = $('.prettyprint').html();
        var nice = JSON.stringify(JSON.parse(html), null, 4);
        $('.prettyprint').html(nice);
    </script>
    
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
@endpush