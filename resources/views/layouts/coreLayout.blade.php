<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Scripts -->
        <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('plugins/switchery/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/morris/morris.css') }}" rel="stylesheet">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/my_style.css') }}" rel="stylesheet">

        @yield('style')

        <script src="{{ asset('js/modernizr.min.js') }}"></script>

        <!-- Axios -->
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    </head>
    <body class="fixed-left">
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.topbar')
            @include('layouts.components.left-side-bar')

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    @include('flash-message')
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>

                <footer class="footer text-right">
                    2017 © Minton.
                </footer>
            </div>

            @include('layouts.components.right-side-bar')

        </div>

        @yield('modal')

        <script>
            var resizefunc = [];
        </script>

        <!-- Plugins  -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/detect.js') }}"></script>
        <script src="{{ asset('js/fastclick.js') }}"></script>
        <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('js/waves.js') }}"></script>
        <script src="{{ asset('js/wow.min.js') }}"></script>
        <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('plugins/switchery/switchery.min.js') }}"></script>
        
        <!-- Counter Up  -->
        <script src="{{ asset('plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('plugins/counterup/jquery.counterup.min.js') }}"></script>

        <!--Morris Chart-->
        <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
        <script src="{{ asset('plugins/raphael/raphael-min.js') }}"></script>

        <!-- Page js  -->
        <script src="{{ asset('pages/jquery.dashboard.js') }}"></script>

        @yield('script')

        <!-- Custom main Js -->
        <script src="{{ asset('js/jquery.core.js') }}"></script>
        <script src="{{ asset('js/jquery.app.js') }}"></script>
        
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
            });
        </script>

        
    </body>
</html>