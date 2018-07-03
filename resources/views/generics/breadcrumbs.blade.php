<ol class="breadcrumb">
    @foreach($items as $item)
        <li>
            @if(array_key_exists('route', $item))
                @if(is_array($item['route']))
                    <a href="{{ route($item['route'][0], $item['route'][1]) }}">{{ $item['text'] }}</a>
                @else
                    <a href="{{ route($item['route']) }}">{{ $item['text'] }}</a>
                @endif
            @elseif(array_key_exists('url', $item))
                <a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
            @else
                <a href="">{{ $item['text'] }}</a>
            @endif
        </li>
    @endforeach
</ol>