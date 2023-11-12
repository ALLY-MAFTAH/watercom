<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') | {{ setting('App Name', "Tanga Watercom") }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/water.png') }}">


    {{-- DATA TABLE --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" media="print" href="{{ asset('css/print.css') }}">


    @notifyCss
    @yield('style')
    <style>
        .notify {
            padding-top: 70px;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>

<body id="body-pd">
    @guest
        <div></div>
    @else
        <div class="header shadow" id="header">
            <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>

            <div class="header_toggle ">
                <h3
                    style="text-shadow: 0.5px 0.5px white;font-family:Verdana, Geneva, Tahoma, sans-serif;color:var(--first-color)">
                    {{-- <b> TANGA WATER COM</b> --}}
                    <b>{{setting('App Name',"Tanga Watercom Depot")}}</b>
                </h3>
            </div>
            {{-- <a href="{{route('test_sms')}}" class="btn btn-dark">Send SMS</a> --}}
            @if (request()->routeIs('carts.index'))
                <div class="cart-dropdown">

                    <a href="#" class="btn btn-sm btn-warning collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#cartCollapse" aria-expanded="false" aria-controls="cartCollapse">

                        <i class="fa fa-shopping-cart shadow" aria-hidden="true"></i> Cart <span
                            class="badge badge-pill badge-danger">
                            <div class="btn btn-icon round "
                                style="height: 23px;width:23px;margin:0;padding: 0;font-size: 12px;line-height:1.3rem; border-radius:50%;background-color:green;color:white">
                                {{ count((array) session('cart')) }}
                            </div>
                        </span>

                    </a>
                </div>
            @endif
            <div class="dropdown prof">
                <a href="#"class="dropdown-toggle" style="text-decoration: none; color:var(--first-color)"
                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-user"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <div class="text-center"><i class="bx bx-user bx-md bx-white"
                                style="width: 40px; height: 40px;color:white;border-radius: 50%; overflow: hidden;background: var(--first-color)"></i>
                        </div><a class="dropdown-item" href="#">{{ Auth::user()->name }}</a>
                    </li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    @if (Auth::user()->role_id == 1)
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a></li>
                        <li><a class="dropdown-item" href="{{ route('logs.index') }}">Activity Logs</a></li>
                    @endif
                    <li>
                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal"
                            aria-expanded="false" aria-controls="collapseTwo">
                            Change Password
                        </a>
                    </li>
                    <hr>
                    <li>
                        <a href="#"
                            onclick="event.preventDefault();if(confirm('Are you sure want to logout ?'))
                document.getElementById('logout-form').submit();"
                            class="text-danger dropdown-item">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="l-navbar" id="nav-bar">
            <a href="#" class="app_logo">
                <div class="header_img"><img style="background: white" src="{{ asset('images/water.png') }}"
                        alt="">
                </div>
            </a>
            <nav class="nav">
                <div class="nav_list overflow-auto vh-100">
                    <a href="{{ route('home') }}"
                        class="nav_link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class='bx bx-home nav_icon'></i> <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="{{ route('stocks.index') }}"
                        class="nav_link {{ request()->routeIs('stocks.index') || request()->routeIs('stocks.show') ? 'active' : '' }}">
                        <i class='bx bx-layout nav_icon'></i> <span class="nav_name">Stocks</span> </a>
                    {{-- <a href="{{ route('products.index') }}"
                        class="nav_link {{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'active' : '' }}">
                        <i class='fa fa-product-hunt fa-lg nav_icon'></i> <span class="nav_name">Products</span>
                    </a> --}}
                    <a href="{{ route('carts.index') }}"
                        class="nav_link {{ request()->routeIs('carts.index') ? 'active' : '' }}">
                        <i class='bx bx-cart nav_icon'></i> <span class="nav_name">Selling Cart</span>
                    </a>
                    <a href="{{ route('sales.index') }}"
                        class="nav_link {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'active' : '' }}">
                        <i class='bx bx-dollar nav_icon'></i> <span class="nav_name">Sales</span> </a>
                    <a href="{{ route('unpaid_sales.index') }}"
                        class="nav_link {{ request()->routeIs('unpaid_sales.index') || request()->routeIs('unpaid_sales.show') ? 'active' : '' }}">
                        <i class='bx bx-credit-card nav_icon'></i> <span class="nav_name">Unpaid Sales</span> </a>
                    <a href="{{ route('customers.index') }}"
                        class="nav_link {{ request()->routeIs('customers.index') || request()->routeIs('customers.show') ? 'active' : '' }}">
                        <i class='fa fa-users fa-lg nav_icon'></i> <span class="nav_name">Customers</span> </a>
                    <a href="{{ route('orders.index') }}"
                        class="nav_link {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') ? 'active' : '' }}">
                        <i class='fa fa-first-order fa-lg nav_icon'></i> <span class="nav_name">Orders</span> </a>
                    @if (Auth::user()->role_id == 1)
                        <a href="{{ route('users.index') }}"
                            class="nav_link {{ request()->routeIs('users.index') || request()->routeIs('users.show') ? 'active' : '' }}">
                            <i class='bx bx-group nav_icon'></i> <span class="nav_name">Users</span>
                        </a>
                        <a href="{{ route('roles.index') }}"
                            class="nav_link {{ request()->routeIs('roles.index') || request()->routeIs('roles.show') ? 'active' : '' }}">
                            <i class='bx bx-registered nav_icon'></i> <span class="nav_name">Roles</span>
                        </a>
                    @endif
                    <a href="{{ route('reports.index') }}"
                        class="nav_link {{ request()->routeIs('reports.index') ? 'active' : '' }}"> <i
                            class='bx bx-book nav_icon'></i> <span class="nav_name">Reports</span>
                    </a>
                    <a href="{{ route('expenses.index') }}"
                        class="nav_link {{ request()->routeIs('expenses.index') ? 'active' : '' }}"> <i
                            class='bx bx-money nav_icon'></i> <span class="nav_name">Expenses</span>
                    </a>

                </div>
            </nav>

        </div>
        <br><br>
        <div class="modal modal-sm fade" id="changePasswordModal" aria-labelledby="changePasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('users.change-password') }}">
                            @method('PUT')
                            @csrf
                            <input type="text" name="user_id" value="{{ Auth::user()->id }}" hidden>

                            <div class="text-start mb-1">
                                <label for="current_password"
                                    class=" col-form-label text-sm-start">{{ __('Current Password') }}</label>
                                <input id="current_password" type="password" placeholder=""
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    name="current_password" value="{{ old('current_password') }}" required
                                    autocomplete="" autofocus>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-start mb-1">
                                <label for="new_password"
                                    class=" col-form-label text-sm-start">{{ __('New Password') }}</label>
                                <input id="new_password" type="password" placeholder=""minlength="8" maxlength="15"
                                    class="form-control @error('new_password') is-invalid @enderror" name="new_password"
                                    value="{{ old('new_password') }}" required autocomplete="new_password" autofocus>
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-start mb-1">
                                <label for="password_confirmation"
                                    class=" col-form-label text-sm-start">{{ __('Confirm New Password') }}</label>
                                <input id="password_confirmation" type="password" placeholder=""minlength="8"
                                    maxlength="15"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" value="{{ old('password_confirmation') }}" required
                                    autocomplete="password_confirmation" autofocus>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row mb-1 mt-3">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <main class="pt-5">
        @yield('content')
    </main>

    </div>
    {{-- <x:notify-messages /> --}}
    @yield('scripts')
    @notifyJs
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script src="https://use.fontawesome.com/3076bdb328.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    {{-- DATA TABLE --}}
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>


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
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
                $('a').attr('disabled', 'disabled');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            if (window.location.href.indexOf("orders") > -1) {
                $(document).on('submit', 'form', function() {
                    $('button').removeAttr('disabled');
                });
            }
        });
    </script>
    <script>
        $(document).on('submit', '#send-order-form', function() {
            console.log('Form submitted');
            $('#send-order-btn').addClass('disabled');
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#data-tebo1').DataTable({
                dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                lengthChange: true,
                columnDefs: [{
                    visible: true,
                    targets: '_all'
                }, ],
                buttons: [{
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                        // messageTop: 'DATAAAAAAAAAA'

                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        },
                        margin: [20, 20, 20, 20],
                        padding: [20, 20, 20, 20],
                        customize: function(doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.widths[0] = 'auto';
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ]
            });
            table.buttons().container()
                .appendTo('#data-tebo1_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#data-tebo2').DataTable({
                dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                // dom: "Bfrtip",

                lengthChange: true,
                columnDefs: [{
                    visible: true,
                    targets: '_all'
                }, ],
                buttons: [{
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        },
                        // messageTop: 'DATAAAAAAAAAA'

                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        },
                        margin: [20, 20, 20, 20],
                        padding: [20, 20, 20, 20],
                        customize: function(doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.widths[0] = 'auto';
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ]
            });
            table.buttons().container()
                .appendTo('#data-tebo2_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            let counter = 0;

            $(".add-more").click(function() {
                var html = $(".copy").html();
                $(".after-add-more").after(html);

                // Update the id of the new elements
                $(".after-add-more").last().find("[id]").each(function() {
                    $(this).attr("id", $(this).attr("id") + "_" + counter);
                });
                counter++;
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
    <script>
        $(document).ready(function() {
            $('#new_password, #password_confirmation').on('input', function() {
                var newPassword = $('#new_password').val();
                var passwordConfirmation = $('#password_confirmation').val();

                if (newPassword != passwordConfirmation) {
                    $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
                    $('#password_confirmation + .invalid-feedback').text(
                        'The new password and password confirmation fields do not match.');
                } else {
                    $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
                    $('#password_confirmation + .invalid-feedback').text('');
                }
            });
        });
    </script>

</body>

</html>
