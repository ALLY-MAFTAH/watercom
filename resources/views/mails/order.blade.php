<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') | {{ 'Morning Sky General' }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/water.png') }}">

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
    </style>
</head>

<body id="body-pd">

    <div class="row justify-content-center">

        <p style="font-size: 14px"><b>This is new order.</b></p>
        <br><br>

        <div>
            <table class="table table-stripped" id="order-summary">
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td class="text-start">
                                {{ $item['volume'] . ' ' . $item['measure'] . ' - ' . $item['name'] }}
                            </td>
                            <td class="text-start" style="padding-left:20px">
                                {{ $item['quantity'] . ' ' . $item['unit'] }}</td>
                        </tr><br>
                    @endforeach
                </tbody>
            </table>
        </div>

        Regards,<br>
        Morning Sky General


    </div>
    <x:notify-messages />
    @yield('scripts')
    @notifyJs
    <script src="https://use.fontawesome.com/3076bdb328.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#data-tebo1').DataTable();
        });
    </script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</body>

</html>
