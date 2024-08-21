@extends('layouts.app')
@section('title')
    Unpaid Sales
@endsection
@section('style')
@endsection
@section('content')
    @php
        $cartProducts = [];
    @endphp
    <br>
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col-5">
                    <h5 class="my-0">
                        <b>{{ __('Unpaid Sales as per customers') }}</b>
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @php
                $totalAmount = 0;
            @endphp
            <table id="data-tebo2" class=" dt-responsive  table shadow rounded-3 table-responsive table-striped">
                <thead class="shadow rounded-3">
                    <th>#</th>
                    <th>Date</th>
                    <th>Bought Products </th>
                    <th class="text-right">Total Amount</th>
                    <th style="width: 100px">Customer</th>
                    <th>Seller</th>
                    <th class="text-center">Status</th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($boughtUnpaidGoods as $index => $unpaidGood)
                        <tr>
                            <form id="ver_pay_form-{{ $unpaidGood->id }}"
                                action="{{ route('unpaid_sales.verify_payment', $unpaidGood) }}" method="post">
                                @csrf
                                <td>{{ ++$index }}</td>
                                <td style="max-width: 100px">
                                    {{ Illuminate\Support\Carbon::parse($unpaidGood->date)->format('D, d M Y \a\t H:i:s') }}
                                </td>
                                <td>
                                    @foreach ($unpaidGood->unpaidPurchases as $purchase)
                                        <div class="row">
                                            <div class="col">
                                                {{ $purchase->name }} {{ $purchase->volume }} {{ $purchase->measure }}
                                            </div>
                                            <div class="col">
                                                {{ $purchase->category }}
                                            </div>
                                            <div class="col">
                                                <input type="number" style="width: 80px" max="{{ $purchase->quantity }}"
                                                    class="form-control @error('new_quantity', $purchase->quantity) is-invalid @enderror"
                                                    name="new_quantity[{{ $purchase->id }}]"
                                                    value="{{ old('new_quantity', $purchase->quantity) }}" required
                                                    autocomplete="new_quantity" autofocus>
                                            </div>
                                            {{ $purchase->unit }}
                                            <div class="col">
                                                <input type="hidden" name="purchase_id[]" value="{{ $purchase->id }}">
                                            </div>
                                            <div class="col text-end">
                                                {{ number_format($purchase->price, 0, '.', ',') }} Tsh
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </td>
                                @php
                                    $total = $total + $unpaidGood->amount_paid;
                                @endphp
                                <td class="text-end"><b>{{ number_format($unpaidGood->amount_paid, 0, '.', ',') }} Tsh</b></td>
                                @php
                                    $customer = App\Models\Customer::find($unpaidGood->customer_id);
                                @endphp
                                <td>
                                    @if ($customer)
                                        <a style="text-decoration: none" href="{{ route('customers.show', $customer) }}">
                                            {{ $customer->name }}</a>
                                    @else
                                        Customer not recorded
                                    @endif
                                </td>
                                <td>{{ $unpaidGood->seller }}</td>
                            </form>
                            <td class="text-center">
                                @if ($unpaidGood->status == 1)
                                    <span class="text-success" style="font-weight: bold"> PAID</span>
                                @else
                                    <span class="text-danger"style="font-weight: bold">NOT PAID</span>
                                @endif
                            </td>
                            <td class="text-center">

                                @if ($unpaidGood->status == 1)
                                    <a href="#" class="mt-2 btn btn-sm btn-outline-danger mx-2"
                                        onclick="if(confirm('Are you sure want to delete?')) document.getElementById('delete-unpaidGood-{{ $unpaidGood->id }}').submit()">
                                        Delete
                                    </a>
                                    <form id="delete-unpaidGood-{{ $unpaidGood->id }}" method="post"
                                        action="{{ route('unpaid_goods.delete', $unpaidGood) }}">@csrf
                                        @method('delete')
                                    </form>
                                @else
                                    <a href="#"onclick="if(confirm('Are you sure want to verify this transaction as paid?')) document.getElementById('ver_pay_form-{{ $unpaidGood->id }}').submit()"
                                        class="mt-2 btn btn-success btn-sm">Verify Payment</a>
                                @endif
                            </td>
                            <td>
                                @if ($unpaidGood->status == 0)
                                    <a href="#"onclick="if(confirm('Are you sure want to discard this transaction?')) document.getElementById('discard-unpaidGood-{{ $unpaidGood->id }}').submit()"
                                        class="mt-2 btn btn-danger btn-sm">Discard</a>
                                    <form id="discard-unpaidGood-{{ $unpaidGood->id }}" method="post"
                                        action="{{ route('unpaid_goods.discard', $unpaidGood) }}">@csrf
                                        @method('delete')
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

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
    <script>
        $(document).ready(function() {
            $('.add-to-cart-form').submit(function(e) {
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
    </script>
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
