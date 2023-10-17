@extends('layouts.app')

@section('title')
    Dashboard | {!! config('app.name') !!}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
@endsection
@section('content')
    <div class="card"id="printable-content">
        <div class="content-wrapper pb-0">
            <div class="page-header flex-wrap">
                <h3 class="mb-0"> Dashboard <span class="pl-0 h6 pl-sm-2 text-muted d-inline-block">Our Business Perfomance
                        Summary</span>
                </h3>
                <div class="d-flex">
                    <button onclick="saveWebpage()" type="button" class="btn btn-sm bg-white btn-icon-text border">
                        <i class="mdi mdi-download btn-icon-prepend"></i> Save </button>
                    <button onclick="printDiv('printable-content')" type="button"
                        class="btn btn-sm bg-white btn-icon-text border ml-3">
                        <i class="mdi mdi-printer btn-icon-prepend"></i> Print </button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-12 grid-margin">
                    <div class="row">
                        <div class="col-xl-12 pb-2 col-md-6">
                            <div class="card bg-success">
                                <div class="card-body px-3 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="color-card">
                                            <p class="mb-0 color-card-head">Today's Total Sales</p>
                                            <h2 class="text-white"> {{ number_format($todaysTotalAmount, 0, '.', ',') }} Tsh
                                            </h2>
                                        </div>
                                        <i class="card-icon-indicator mdi mdi-currency-usd bg-inverse-icon-success"></i>
                                    </div>
                                    <h6 class="text-white">
                                        @if ($todaysLeadingProduct != [])
                                            {{ $todaysLeadingProduct->name . ' ' . $todaysLeadingProduct->volume . ' ' . $todaysLeadingProduct->measure }}
                                            is leading
                                    </h6>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 pb-2 col-md-6 ">
                            <div class="card bg-warning">
                                <div class="card-body px-3 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="color-card">
                                            <p class="mb-0 color-card-head">Today's Water Sales</p>
                                            <h2 class="text-white">
                                                {{ number_format($todaysWaterAmount, 0, '.', ',') }} Tsh
                                            </h2>
                                        </div>
                                        <i class="card-icon-indicator mdi mdi-water bg-inverse-icon-warning"></i>
                                    </div>
                                    <h6 class="text-white">
                                        @if ($todaysLeadingWater != [])
                                            {{ $todaysLeadingWater->name . ' ' . $todaysLeadingWater->volume . ' ' . $todaysLeadingWater->measure }}
                                            is leading
                                    </h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 pb-2 col-md-6 ">
                            <div class="card bg-danger">
                                <div class="card-body px-3 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="color-card">
                                            <p class="mb-0 color-card-head">Today's Soda Sales</p>
                                            <h2 class="text-white"> {{ number_format($todaysSodaAmount, 0, '.', ',') }} Tsh
                                            </h2>
                                        </div>
                                        <i class="card-icon-indicator mdi mdi-cup bg-inverse-icon-danger"></i>
                                    </div>
                                    <h6 class="text-white">
                                        @if ($todaysLeadingSoda != [])
                                            {{ $todaysLeadingSoda->name . ' ' . $todaysLeadingSoda->volume . ' ' . $todaysLeadingSoda->measure }}
                                            is leading
                                    </h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 pb-2 col-md-6 ">
                            <div class="card bg-primary">
                                <div class="card-body px-3 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="color-card">
                                            <p class="mb-0 color-card-head">Available Stock Worth</p>
                                            <h2 class="text-white">Tsh {{ number_format($stockAmount, 0, '.', ',') }}</h2>
                                        </div>
                                        <i class="card-icon-indicator mdi mdi-basket bg-inverse-icon-primary"></i>
                                    </div>
                                    <h6 class="text-white">Today
                                        {{ Illuminate\Support\Carbon::parse(now())->format('d M, Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 stretch-card grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h5>Sales Statistics</h5>
                                    <p class="text-muted"> Show overview {{ date('M, Y') }}. <a
                                            class="text-primary btn-icon-text font-weight-medium pl-2"
                                            style="text-decoration:none" href="{{ route('sales.index') }}">See
                                            Details</a>
                                    </p>
                                </div>
                                <div class="col-sm-5 text-right">
                                    <a href="{{ route('sales.index') }}" type="button"
                                        style="background: rgba(196, 184, 243, 0.688)"
                                        class="btn btn-icon-text mb-3 mb-sm-0 btn-inverse-primary font-weight-normal">
                                        <i class="mdi mdi-currency-usd btn-icon-prepend"></i>Go To Sales Page </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card mb-3 mb-sm-0">
                                        <div class="card-body py-3 px-4">
                                            <p class="m-0 survey-head">This Week Sales</p>
                                            <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                                <div>
                                                    <h3 class="m-0 survey-value">Tsh
                                                        {{ number_format($thisWeekSalesAmount, 0, '.', ',') }}</h3>
                                                    @if (Auth::user()->role->id == 1)
                                                        <h5 class="text-success m-0">Profit:
                                                            {{ number_format($thisWeekProfit, 0, '.', ',') }} Tsh</h5>
                                                    @endif
                                                </div>
                                                <div id="productChart" class="flot-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card mb-3 mb-sm-0">
                                        <div class="card-body py-3 px-4">
                                            <p class="m-0 survey-head">This Month Sales</p>
                                            <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                                <div>
                                                    <h3 class="m-0 survey-value">Tsh
                                                        {{ number_format($thisMonthSalesAmount, 0, '.', ',') }}</h3>
                                                    @if (Auth::user()->role->id == 1)
                                                        <h5 class="text-success m-0">Profit:
                                                            {{ number_format($thisMonthProfit, 0, '.', ',') }} Tsh</h5>
                                                    @endif
                                                </div>
                                                <div id="earningChart" class="flot-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body py-3 px-4">
                                            <p class="m-0 survey-head">Today's Total Customers</p>
                                            <div class="d-flex justify-content-between align-items-end flot-bar-wrapper">
                                                <div>
                                                    <h3 class="m-0 survey-value">{{ $todaysTotalCustomers }} customers</h3>
                                                    {{-- <p class="text-success m-0">-310 avg. sales</p> --}}
                                                </div>
                                                <div id="orderChart" class="flot-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-sm-12">
                                    <div class="flot-chart-wrapper">
                                        <div id="flotChart" class="flot-chart">
                                            <canvas class="flot-base"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">

                                </div>
                                @if (Auth::user()->role_id == 1)
                                    <div class="col-sm-4">
                                        <p class="mb-0 text-muted">Sales Revenue</p>
                                        <h5 class="d-inline-block survey-value mb-0"> Tsh
                                            {{ number_format($salesRevenue, 0, '.', ',') }} </h5>
                                        <p class="d-inline-block text-success mb-0"> Since January, this Year </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-xl-3 col-md-6 mb-2">
                    <div class="card">
                        <div class="p-3">
                            <h4 class="card-title text-black">Today's Most Expensed Customers</h4>
                            <p class="text-muted">Top 5</p>
                            @foreach ($todaysTopSales as $topSale)
                                <div class="row pt-2 pb-1">
                                    <div class="col-3">
                                        <i class="mdi mdi-account-circle" style="font-size: 25px"></i>
                                    </div>
                                    <div class="col-9">
                                        @php
                                            $customer = App\Models\Customer::find($topSale->customer_id);
                                        @endphp
                                        <h5 class="mb-0">
                                            @if ($customer)
                                                <a style="text-decoration: none"
                                                    href="{{ route('customers.show', $customer) }}">
                                                    {{ $customer->name }}</a>
                                            @else
                                                Customer not recorded
                                            @endif
                                        </h5>
                                        <h5 class="text-muted">{{ number_format($topSale->amount_paid, 0, '.', ',') }} Tsh
                                        </h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-black">Products Statistics</h4>
                            <p class="text-muted pb-2">Jan 01 2023 - Jan 23 2023</p>
                            <canvas id="surveyBar"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-md-6 grid-margin stretch-card">
                    <div class="card card-invoice">
                        <div class="card-body">
                            <h4 class="card-title pb-3">Pending Payments</h4>
                            <div class="list-card">
                                <div class="row align-items-center">
                                    <div class="col-7 col-sm-8">
                                        <div class="row align-items-center">

                                            <div class="col-sm-8 pr-0 pl-sm-0">
                                                <span>06 Jan 2019</span>
                                                <h6 class="mb-1 mb-sm-0">Isabel Cross</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5 col-sm-4">
                                        <div class="d-flex pt-1 align-items-center">

                                            <div class="dropdown dropleft pl-1 pt-3">
                                                <div id="dropdownMenuButton2" data-toggle="dropdown" role="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <p><i class="mdi mdi-dots-vertical"></i></p>
                                                </div>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                    <a class="dropdown-item" href="#">Sales</a>
                                                    <a class="dropdown-item" href="#">Track Invoice</a>
                                                    <a class="dropdown-item" href="#">Payment
                                                        History</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-card">
                                <div class="row align-items-center">
                                    <div class="col-7 col-sm-8">
                                        <div class="row align-items-center">

                                            <div class="col-sm-8 pr-0 pl-sm-0">
                                                <span>18 Mar 2019</span>
                                                <h6 class="mb-1 mb-sm-0">Carrie Parker</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5 col-sm-4">
                                        <div class="d-flex pt-1 align-items-center">
                                            <div class="reload-outer bg-primary">
                                                <i class="mdi mdi-reload"></i>
                                            </div>
                                            <div class="dropdown dropleft pl-1 pt-3">
                                                <div id="dropdownMenuButton3" data-toggle="dropdown" role="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <p><i class="mdi mdi-dots-vertical"></i></p>
                                                </div>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                                    <a class="dropdown-item" href="#">Sales</a>
                                                    <a class="dropdown-item" href="#">Track Invoice</a>
                                                    <a class="dropdown-item" href="#">Payment
                                                        History</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-card">
                                <div class="row align-items-center">
                                    <div class="col-7 col-sm-8">
                                        <div class="row align-items-center">

                                            <div class="col-sm-8 pr-0 pl-sm-0">
                                                <span>10 Apr 2019</span>
                                                <h6 class="mb-1 mb-sm-0">Don Bennett</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5 col-sm-4">
                                        <div class="d-flex pt-1 align-items-center">

                                            <div class="dropdown dropleft pl-1 pt-3">
                                                <div id="dropdownMenuButton4" data-toggle="dropdown" role="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <p><i class="mdi mdi-dots-vertical"></i></p>
                                                </div>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                                    <a class="dropdown-item" href="#">Sales</a>
                                                    <a class="dropdown-item" href="#">Track Invoice</a>
                                                    <a class="dropdown-item" href="#">Payment
                                                        History</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-card">
                                <div class="row align-items-center">
                                    <div class="col-7 col-sm-8">
                                        <div class="row align-items-center">

                                            <div class="col-sm-8 pr-0 pl-sm-0">
                                                <span>18 Mar 2019</span>
                                                <h6 class="mb-1 mb-sm-0">Carrie Parker</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5 col-sm-4">
                                        <div class="d-flex pt-1 align-items-center">
                                            <div class="reload-outer bg-info">
                                                <i class="mdi mdi-reload"></i>
                                            </div>
                                            <div class="dropdown dropleft pl-1 pt-3">
                                                <div id="dropdownMenuButton5" data-toggle="dropdown" role="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <p><i class="mdi mdi-dots-vertical"></i></p>
                                                </div>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
                                                    <a class="dropdown-item" href="#">Sales</a>
                                                    <a class="dropdown-item" href="#">Track Invoice</a>
                                                    <a class="dropdown-item" href="#">Payment
                                                        History</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-4 col-md-6 stretch-card grid-margin stretch-card">
                    <!--browser stats-->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">High Percentage Profits by Products</h4>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Orange</span>
                                        </div>
                                        <p class="mb-0">95%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Malta</span>
                                        </div>
                                        <p class="mb-0">93%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Passion</span>
                                        </div>
                                        <p class="mb-0">83%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Water</span>
                                        </div>
                                        <p class="mb-0">73%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Berries</span>
                                        </div>
                                        <p class="mb-0">70%</p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!--browser stats ends-->
                </div>
                <div class="col-xl-4 col-md-6 stretch-card grid-margin stretch-card">
                    <!--browser stats-->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Low Percentage Profits by Products</h4>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Lemon</span>
                                        </div>
                                        <p class="mb-0">43%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Embe</span>
                                        </div>
                                        <p class="mb-0">43%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Berry Mix</span>
                                        </div>
                                        <p class="mb-0">26%</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Malta Apple</span>
                                        </div>
                                        <p class="mb-0">13%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-2">
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-between pb-3 border-bottom">
                                        <div>

                                            <span class="p">Orange</span>
                                        </div>
                                        <p class="mb-0">23%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--browser stats ends-->
                </div>
            </div>
        </div>
    </div>
    <footer class="py-3">
        <div class=" text-center">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                Morning Sky General - {{ date('Y') }}</span>
        </div>
    </footer>
@endsection
@section('scripts')
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script>
        function saveWebpage() {
            var pageTitle = document.title;
            var pageUrl = window.location.href;
            var html = document.documentElement.outerHTML;
            var now = new Date();
            var dateString = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" + now.getDate();

            var blob = new Blob([html], {
                type: "text/html;charset=utf-8"
            });
            saveAs(blob, dateString + " " + pageTitle + ".html");
        }

        function saveAs(blob, filename) {
            if (navigator.msSaveBlob) {
                // IE10+
                navigator.msSaveBlob(blob, filename);
            } else {
                var link = document.createElement("a");
                // Browsers that support HTML5 download attribute
                if (link.download !== undefined) {
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", filename);
                    link.style.visibility = "hidden";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        }
    </script>
@endsection
