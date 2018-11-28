@extends('layout.app')

@push('sidebar')
    <nav id="myScrollspy">
        <ul class="nav nav-pills nav-stacked" data-offset-top="500">
            <li class="active"><a href="#home">Basic Sidenav</a></li>
            <li><a href="#section1">Section 1</a></li>
            <li><a href="#section2">Section 2</a></li>
            <li><a href="#section3">Section 3</a></li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Section 4 <span class="caret"></span</a>
                <ul class="dropdown-menu">
                    <li><a href="#section41">Section 4-1</a></li>
                    <li><a href="#section42">Section 4-2</a></li>
                </ul>
            </li>
        </ul>
    </nav>
@endpush

@section('content')
    <div class="page-header">
        <h1>Home</h1>
    </div>
    @if(Auth::check())
        <h1>sup {{ Auth::user()->username }}</h1>
    @else
        <h1>Who?</h1>
    @endif
    
    
    <div class="col-sm-9">
        <div id="section1">
            <h1>Section 1</h1>
            <p>Affix plugin:</p>
            <p>1) allows an element to become locked to an area on the page</p>
            <p>2) usually used with nav menus or social icon buttons</p>
        </div>
        <div id="section2">
            <h1>Section 2</h1>
            <p>3) plugin toggles css position from static to fixed, depending on the scroll position</p>
            <p>4) e.g. affixed navbar / affixed sidebar</p>
            <p>5) add data-spy="affix" to element you want affixed</p>
        </div>
        <div id="section3">
            <h1>Section 3</h1>
            <p>6) (optional) add data-offset-top|bottom to calculate position of the scroll</p>
            <p>7) affix plugin toggles between the 3 classes: .affix, .affix-top, and .affix-bottom</p>
            <p>8) add css properties to handle the actual positions, except for position: fixed on the .affix class</p>
        </div>
        <div id="section41">
            <h1>Section 4-1</h1>
            <p>9) plugin adds the .affix-top and .affix-bottom class to indicate the element in its top-most or bottom-most position. positioning with css is not required at this point.</p>
            <p>10) scrolling past the affixed element triggers the actual affixing - this is where the plugin replaces the .affix-top or .affix-bottom class with the .affix class (sets position: fixed). at this point, you must add the css top or bottom property to position the affixed element in the page.</p>
            <p>11) if a bottom offset is defined, scrolling past it replaces the .affix class with the .affix-bottom. since offsets are optional, setting one requires the appropriate css. in this case, add position: absolute when necessary.</p>
        </div>
        <div id="section42">
            <h1>Section 4-2</h1>
            <p>12) in the previous eg, the affix plugin adds the .affix class (position: fixed) to the &lt;nav&gt; element after scrolling 197 pixels from the top. we added the css top property with a value of 0 to the .affix class. this is to make sure that the navbar stays at the top all the time, when we have scrolled 197 pixels from the top.</p>
            <br><br>
            <p>Other things:</p>
            <p>bootstrap grid system: clear floats (.clearfix class), offsetting columns, push and pull to change column ordering</p>
            <p>templates: blog, portfolio, webpage, social, marketing, analytics, online store</p>
            <p>!!themes***</p>
            <p>!!quiz</p>
            <p>!!js ref</p>
        </div>
    
        <div  style="min-height: 1000px;">
            <h1>sds</h1>
        </div>
    </div>
@endsection