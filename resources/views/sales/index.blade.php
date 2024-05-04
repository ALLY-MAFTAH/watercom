@extends('layouts.app')
@section('title')
    Sales
@endsection
@section('style')
@endsection
@section('content')
    @php
        $cartProducts = [];
    @endphp
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col-5">
                    <h5 class="my-0">
                        <b>{{ __('Sales as per individual products') }}</b>
                    </h5>
                </div>
                <div class="col-7">
                    <form action="{{ route('sales.index') }}" method="GET" id="filter-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="input-group">
                                    <label for="filteredStockId" class=" col-form-label">Filter By Product:
                                    </label>
                                    <select name="filteredStockId" id="filteredStockId"
                                        class="form-select  mx-1 form-control" style="border-radius:0.375rem;"
                                        onchange="this.form.submit()">
                                        <option value='All Products'
                                            {{ $filteredStockId == 'All Products' ? 'selected' : '' }}>
                                            All Products
                                        </option>
                                        @foreach ($allStocks as $stock)
                                            <option value="{{ $stock->id }}"
                                                {{ $filteredStockId == $stock->id ? 'selected' : '' }}>
                                                {{ $stock->name . '-' . $stock->volume . ' ' . $stock->measure }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="input-group">

                                    <label for="filteredDate" class=" col-form-label">Filter By Date: </label>
                                    <input id="filteredDate" type="date" style="border-radius:0.375rem;"
                                        class="form-control mx-1 @error('filteredDate') is-invalid @enderror"
                                        name="filteredDate" value="{{ $filteredDate }}" required
                                        onchange="this.form.submit()">
                                    @error('filteredDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @php
                $totalAmount = 0;
            @endphp

            <table id="data-tebo1"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="rounded-3 shadow ">
                    <th style="max-width: 20px">#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th class="text-right">Price</th>
                    <th>Date</th>
                    <th>Seller</th>
                </thead>
                <tbody>
                    @foreach ($sales as $index => $sale)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $sale->name . ' - ' . $sale->volume . ' ' . $sale->measure }}</td>
                            <td>{{ $sale->category }}</td>
                            <td>{{ $sale->quantity . ' ' . $sale->unit }}</td>
                            <td class="text-right">{{ number_format($sale->price, 0, '.', ',') }}
                                Tsh</td>
                            @php
                                $totalAmount += $sale->price;
                            @endphp
                            <td class="">{{ $sale->created_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td>{{ $sale->seller }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="font-size: 18px;font-weight:bold">Total</td>
                    <td style="font-size: 18px;font-weight:bold" class="text-right">
                        {{ number_format($totalAmount, 0, '.', ',') }} Tsh</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <br><br>
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col-5">
                    <h5 class="my-0">
                        <b>{{ __('Sales as per customers') }}</b>
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @php
                $totalAmount = 0;
            @endphp
            <table id="data-tebo2" class=" dt-responsive nowrap table shadow rounded-3 table-responsive table-striped">
                <thead class="shadow rounded-3">
                    <th>#</th>
                    <th>Date</th>
                    <th>Bought Products </th>
                    <th class="text-right">Total Paid Amount</th>
                    <th>Customer</th>
                    <th>Seller</th>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($boughtGoods as $index => $good)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ Illuminate\Support\Carbon::parse($good->created_at)->format('D, d M Y \a\t H:i:s') }}
                            </td>
                            <td>
                                @foreach ($good->purchases as $purchase)
                                    <div>
                                        {{ $purchase->name }} {{ $purchase->volume }} {{ $purchase->measure }} - {{ $purchase->category }} -
                                        {{ $purchase->quantity }} {{ $purchase->unit }},
                                    </div>
                                @endforeach

                            </td>
                            @php
                                $total = $total + $good->amount_paid;
                            @endphp
                            <td class="text-right">{{ number_format($good->amount_paid, 0, '.', ',') }} Tsh</td>
                            @php
                                $customer = App\Models\Customer::find($good->customer_id);
                            @endphp
                            <td>
                                @if ($customer)
                                    <a style="text-decoration: none" href="{{ route('customers.show', $customer) }}">
                                        {{ $customer->name }}</a>
                                @else
                                    Customer not recorded
                                @endif
                            </td>
                            <td>{{ $good->seller }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right">
                        <h5>Total</h5>
                    </td>

                    <td class="text-end">
                        <h5>{{ number_format($total, 0, '.', ',') }} Tsh</h5>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('.add-to-cart-form').submit(function(e) {
                console.log("SUBmiTEDDDDDD:::::::::::");
                e.preventDefault();
                $.ajax({
                    url: '/cart/add',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script> --}}
    <script>
        jQuery(document).ready(function() {
            $(".update-cart").change(function(e) {
                e.preventDefault();

                var ele = $(this);

                $.ajax({
                    url: '{{ route('update.cart') }}',
                    method: "patch",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.parents("tr").attr("data-id"),
                        quantity: ele.parents("tr").find(".quantity").val()
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".remove-from-cart").click(function(e) {
                e.preventDefault();

                var ele = $(this);

                if (confirm("Are you sure want to remove?")) {
                    $.ajax({
                        url: '{{ route('remove.from.cart') }}',
                        method: "DELETE",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: ele.parents("tr").attr("data-id")
                        },
                        success: function(response) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection
