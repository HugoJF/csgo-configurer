<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('/favicon.ico') }}">
    
    <title>CSGO Server Wizard</title>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/dashboard.css') }}" rel="stylesheet">
    
    <link href="{{ asset('/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/summernote.css') }}" rel="stylesheet">
    
    @yield('head')
    
    <style>
        #accordion .glyphicon {
            margin-right: 10px;
        }
        
        .panel-collapse > .list-group .list-group-item:first-child {
            border-top-right-radius: 0;
            border-top-left-radius: 0;
        }
        
        .panel-collapse > .list-group .list-group-item {
            border-width: 1px 0;
        }
        
        .panel-collapse > .list-group {
            margin-bottom: 0;
        }
        
        .panel-collapse .list-group-item {
            border-radius: 0;
        }
        
        .panel-collapse .list-group .active a {
            color: #fff;
        }
    </style>
    
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="{{ asset('/js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
    <script src="{{ asset('/js/ie-emulation-modes-warning.js') }}"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">@lang('messages.toggle-navigation')</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">@lang('messages.navbar-brand')</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('users.settings') }}">@lang('messages.settings')</a></li>
    
                <li><a href="{{ route('logout') }}">@lang('messages.logout')</a></li>
                <li><a href="{{ route('login') }}">@lang('messages.login')</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-sm-3 col-md-2 sidebar">
            
            
            <div class="panel-group" id="accordion">
                
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseZero">
                                <span class="glyphicon glyphicon-asterisk"></span>Create
                            </a>
                        </h4>
                    </div>
                    <div id="collapseZero" class="panel-collapse collapse in">
                        <ul class="list-group">
                            <li class="list-group-item {{ Route::is('bundle.create') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('bundle.create') }}">New Bundle</a>
                            </li>
                            <li class="list-group-item {{ Route::is('template.create') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('template.create') }}">New Template</a>
                            </li>
                            <li class="list-group-item {{ Route::is('server.create') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('server.create') }}">New Server</a>
                            </li>
                            <li class="list-group-item {{ Route::is('installation.create') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('installation.create') }}">New Installation</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <span class="glyphicon glyphicon-star"></span>Indexes
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <ul class="list-group">
                            <li class="list-group-item {{ Route::is('bundle.index') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('bundle.index') }}">Bundles</a>
                            </li>
                            <li class="list-group-item {{ Route::is('template.index') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('template.index') }}">Templates</a>
                            </li>
                            <li class="list-group-item {{ Route::is('server.index') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('server.index') }}">Servers</a>
                            </li>
                            <li class="list-group-item {{ Route::is('installation.index') ? 'active' : ''}}">
                                <span class="glyphicon glyphicon-home"></span>
                                <a id="home" href="{{ route('installation.index') }}">Installations</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                
                
            </div>
        </div>
        
        
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    @php
                        flash()->error($error)->important();
                    @endphp
                @endforeach
            @endif
            
            @include('flash::message')
            
            @yield('content')
        </div>
        </footer>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ asset('/js/vendor/jquery.min.js') }}"><\/script>')</script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script src="{{ asset('/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="{{ asset('/js/vendor/holder.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('/js/ie10-viewport-bug-workaround.js') }}"></script>
<script src="{{ asset('/js/summernote.js') }}"></script>
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
<script>
    var clipboard = new ClipboardJS('.clipboard-js');
</script>
<script>
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').delay(5000).fadeOut(350);
</script>
@stack('scripts')
</body>
</html>