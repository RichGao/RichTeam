<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link href="{{ asset('theme/css/bootstrap-cerulean.min.css') }}" rel="stylesheet" id="bs-css">

    <link href="theme/css/charisma-app.css" rel="stylesheet">
    <link href='theme/bower_components/fullcalendar/dist/fullcalendar.css' rel='stylesheet'>
    <link href='theme/bower_components/fullcalendar/dist/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='theme/bower_components/chosen/chosen.min.css' rel='stylesheet'>
    <link href='theme/bower_components/colorbox/example3/colorbox.css' rel='stylesheet'>
    <link href='theme/bower_components/responsive-tables/responsive-tables.css' rel='stylesheet'>
    <link href='theme/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href='theme/css/jquery.noty.css' rel='stylesheet'>
    <link href='theme/css/noty_theme_default.css' rel='stylesheet'>
    <link href='theme/css/elfinder.min.css' rel='stylesheet'>
    <link href='theme/css/elfinder.theme.css' rel='stylesheet'>
    <link href='theme/css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='theme/css/uploadify.css' rel='stylesheet'>
    <link href='theme/css/animate.min.css' rel='stylesheet'>
    <link href="./assets/erc20/css/black.css" rel="stylesheet" id="stylesheet" />
    <link href="./assets/erc20/css/extend-centra.css" rel="stylesheet" type="text/css" />

    <!-- jQuery -->
    <script src="theme/bower_components/jquery/jquery.min.js"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('images/centra-logo.png') }}" rel="icon" />

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toggle.css') }}" rel="stylesheet">
    <link href="{{ asset('images/centra-logo.png') }}" rel="icon" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top centra-nav">
            <div class="container" style="width:100%;">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand navbar-brand-centra" href="{{ url('/home') }}">
                        <div class="logo"><img src="{{ asset('images/centra-logo.png') }}"/></div>
                    </a>

                    @if (!Auth::guest())
                        <div style="float:left;width:320px;">
                            <div class="switch">
                                <input type="checkbox" name="toggle" >
                                <label for="toggle"><i></i></label>
                                <span></span>
                                <div id="trading_method">Centralized</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}" style="height: 50px;padding: 14px;">Login</a></li>
                            <li><a href="{{ route('register') }}" style="height: 50px;padding: 14px;">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" style="height: 50px;padding: 14px;">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ url('account') }}">
                                            Account
                                        </a>
                                        <a href="{{ url('admin') }}">
                                            Adminpanel
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}"></script>--}}
{{--    <script src="{{ asset('js/app.js') }}"></script>--}}
    <script src="theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

{{--    <script src="{{ asset('./assets/js/jquery-3.1.1.js') }}"></script>--}}
    <!-- library for cookie management -->
    <script src="theme/js/jquery.cookie.js"></script>
    <!-- calender plugin -->
    <script src='theme/bower_components/moment/min/moment.min.js'></script>
    <script src='theme/bower_components/fullcalendar/dist/fullcalendar.min.js'></script>
    <!-- data table plugin -->
    <script src='theme/js/jquery.dataTables.min.js'></script>

    <!-- select or dropdown enhancer -->
    <script src="theme/bower_components/chosen/chosen.jquery.min.js"></script>
    <!-- plugin for gallery image view -->
    <script src="theme/bower_components/colorbox/jquery.colorbox-min.js"></script>
    <!-- notification plugin -->
    <script src="theme/js/jquery.noty.js"></script>
    <!-- library for making tables responsive -->
    {{--<script src="theme/bower_components/responsive-tables/responsive-tables.js"></script>--}}
    <!-- tour plugin -->
    <script src="theme/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
    <!-- star rating plugin -->
    <script src="theme/js/jquery.raty.min.js"></script>
    <!-- for iOS style toggle switch -->
    <script src="theme/js/jquery.iphone.toggle.js"></script>
    <!-- autogrowing textarea plugin -->
    <script src="theme/js/jquery.autogrow-textarea.js"></script>
    <!-- multiple file upload plugin -->
    <script src="theme/js/jquery.uploadify-3.1.min.js"></script>
    <!-- history.js for cross-browser state change on ajax -->
    <script src="theme/js/jquery.history.js"></script>
    <!-- application script for Charisma demo -->
    <script src="theme/js/charisma.js"></script>
<script>
    $('input[name="toggle"]').click(function(){
        if ($(this).is(":checked")) {
            window.location.href="/decentralized";
        }
        else {
            window.location.href="/centralized";
        }
    });
</script>
</body>
</html>
