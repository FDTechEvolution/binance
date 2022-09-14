<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>LOGIN</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('plugins/switchery/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/morris/morris.css') }}" rel="stylesheet">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <script src="{{ asset('js/modernizr.min.js') }}"></script>

    </head>
    <body>
        <div class="wrapper">

            <div class="row">
                <div class="col-md-7 bg-login">
                    
                </div>

                <div class="col-md-5 p-4 center-screen">
                    <div class="w-75 align-self-center">
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="logo-lg"><i class="mdi mdi-radar"></i> <span>Binance</span> </a>
                        </div>

                        <form class="form-horizontal m-t-20" method="POST" action="{{ route('auth') }}">
                        @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="mdi mdi-account"></i></span>
                                        <input class="form-control" type="text" name="username" required="" placeholder="Username" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="mdi mdi-radar"></i></span>
                                        <input class="form-control" type="password" name="password" required="" placeholder="Password">
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form-group row">
                                <div class="col-12 m-t-10">
                                    <div class="m-l-15">
                                        <div class="checkbox checkbox-primary text-left">
                                            <input id="checkbox-signup" type="checkbox">
                                            <label for="checkbox-signup">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="form-group text-right m-t-20">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-custom w-md waves-effect waves-light" type="submit">Log In
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>


<style>
    .center-screen {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 100vh;
    }
    .bg-login {
        background-image: url("{{ asset('images/bg-login.webp') }}");
        background-size: cover;
        background-position: left center;
    }
</style>