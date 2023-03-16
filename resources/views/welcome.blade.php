<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ 'Morning Sky General' }} @yield('title') |</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/icecream.png') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">


    @notifyCss
    @yield('style')
    <style>
        .notify {
            padding-top: 70px;
        }

        .text-center form,
        .text-center a {
            display: block !important;
        }
    </style>
</head>

<body class="container">
    <div class="text-center pt-5">
        <h1 style="color: var(--first-color)">
            <b> MORNING SKY GENERAL</b>
        </h1><br><br>
        {{-- <p class="text-white">Dealers in</p> --}}
        <div class="row">
            <div class="col-sm-4 pb-3">
                <a href="{{ route('watercom.login') }}" style="color: white; text-decoration:none">
                    <div class="card shadow"style="background: var(--first-color)">
                        <div class="card-body">
                            <h5>
                                Water Com
                            </h5>
                            <img class="" height="130px" width="130px" src="{{ asset('images/water.png') }}"
                                alt="">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 pb-3">
                <a href="{{ route('icecream.login') }}" style="color: white; text-decoration:none">
                    <div class="card shadow"style="background: var(--first-color)">
                        <div class="card-body">
                            <h5>
                                Ice Cream Center
                            </h5>
                            <img class="" height="130px" width="130px" src="{{ asset('images/icecream.png') }}"
                                alt="">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 pb-3">
                <a href="{{ route('gas.login') }}" style="color: white; text-decoration:none">
                    <div class="card shadow"style="background: var(--first-color)">
                        <div class="card-body">
                            <h5>
                                Oryx Gas
                            </h5>
                            <img class="" height="130px" width="130px" src="{{ asset('images/gas.png') }}"
                                alt="">
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
<x:notify-messages />
@yield('scripts')
@notifyJs
<script src="https://use.fontawesome.com/3076bdb328.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@vite(['resources/sass/app.scss', 'resources/js/app.js'])

</body>

</html>
