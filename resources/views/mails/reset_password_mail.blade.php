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

        <p style="font-size: 20px"><b>Hello!</b></p>
        <p>
            You are receiving this email because we received a password reset request for your account.
        </p><br><br>

        <a href="{{ route('password.reset', $token) }}"
            style="background:rgb(47, 6, 161);color:white;text-decoration:none; border-radius:5px;padding:10px">RESET
            LINK</a>

        <br><br>
        <p>
            This password reset link will expire in 60 minutes.
        </p>
        <p>
            If you did not request a password reset, no further action is required.
        </p>

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
        document.addEventListener("DOMContentLoaded", function(event) {
            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)
                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show')
                        // change icon
                        toggle.classList.toggle('bx-x')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }
            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')
            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))

            // Your code to run since DOM is loaded and ready
        });
    </script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js;"></script>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js;"></script> --}}
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#data-tebo1').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#data-tebo2').DataTable();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".add-more").click(function() {
                var html = $(".copy").html();
                $(".after-add-more").after(html);
            });

            $("body").on("click", ".remove", function() {
                $(this).parents(".control-group").remove();
            });

        });
    </script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</body>

</html>
