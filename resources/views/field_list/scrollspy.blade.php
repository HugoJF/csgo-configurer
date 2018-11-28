@php
    if(!isset($level)) $level = 1;
@endphp

@foreach ($fieldLists as $fieldList)
    <li><a href="#{{ str_slug($fieldList->name) }}">{{ str_repeat('— ', $level) }}{{ $fieldList->name }}</a></li>
    @include('field_list.scrollspy', ['fieldLists' => $fieldList->children, 'level' => $level + 1])
@endforeach