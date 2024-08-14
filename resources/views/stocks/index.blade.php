@extends('layouts.app')
@section('title')
    Stocks
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <h5 class="my-0">
                            <span class="">
                                <b>{{ __('STOCK')}}</b>
                            </span>
                        </h5>
                    </div>
                </div>

                <div class="col">
                    <form action="{{ route('stocks.index') }}" method="GET" class="mb-0">
                        <div class="row">
                            <div class="col">
                                <label for="date">Date</label>
                                <input type="date" id="date" name="date" class="form-control"
                                    value="{{ request('date', now()->format('Y-m-d')) }}">
                            </div>
                            <div class="col">
                                <label for="time">Time</label>
                                <input type="time" id="time" name="time" class="form-control"
                                    value="{{ request('time', now()->format('H:i')) }}">
                            </div>
                            <div class="col align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="row m-2">
            <div class="col-8">
                <table id="data-tebo1"
                    class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                    style="width: 100%">
                    <thead class="shadow rounded-3">
                        <th style="max-width: 20px">#</th>
                        <th>Name</th>
                        <th>Volume</th>
                        <th class="text-end">Quantity &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Unit</th>
                        {{-- @if (Auth::user()->role_id == 1)
                            <th class="text-end">Buying Price</th>
                        @endif --}}
                        {{-- <th class="text-end">Selling Price</th> --}}
                        {{-- <th class="text-end">Special Price</th>
                <th class="text-end">Refill Price</th> --}}

                    </thead>
                    <tbody>
                        @php
                            $totalBuyingPrice = 0;
                            $totalSellingPrice = 0;
                        @endphp

                        @foreach ($stocks as $index => $stock)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->volume }} {{ $stock->measure }}</td>
                                <td class="text-end">{{ $stockForDateTime[$stock->id] ?? 0 }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>{{ $stock->unit }}</td>
                                {{-- @if (Auth::user()->role_id == 1)
                                    <td class="text-end">{{ number_format($stock->cost, 0, '.', ',') }} Tsh</td>
                                @endif --}}
                                @php
                                    $quantity = $stockForDateTime[$stock->id] ?? 0;
                                    $totalBuyingPrice += $stock->cost * $quantity;
                                    $totalSellingPrice += $stock->product->price * $quantity;
                                @endphp
                                {{-- <td class="text-end">{{ number_format($stock->product->price, 0, '.', ',') }} Tsh</td> --}}
                                {{-- <td class="text-end">{{ number_format($stock->product->special_price, 0, '.', ',') }} Tsh</td>
                        <td class="text-end">{{ number_format($stock->product->refill_price, 0, '.', ',') }} Tsh</td> --}}
                            </tr>
                        @endforeach

                    </tbody>
                    {{-- <tr>
                        <td colspan="3"></td>
                        <td>
                            <h5>Total</h5>
                        </td>
                        <td class="text-end">
                            <h5>{{ number_format($totalBuyingPrice, 0, '.', ',') }} Tsh</h5>
                        </td>
                        <td class="text-end">
                            <h5>{{ number_format($totalSellingPrice, 0, '.', ',') }} Tsh</h5>
                        </td>
                    </tr> --}}
                </table>
            </div>
            <div class="col-4 ">
                <div class="card">
                    <div class="card-body">
                        <h4> Stock Value Base On Buying Price</h4>
                        <br>
                        <h3> <b>{{ number_format($totalBuyingPrice, 0, '.', ',') }}</b> Tsh</h3>
                    </div>

                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <h4> Stock Value Base On Selling Price</h4>
                        <br>
                        <h3> <b>{{ number_format($totalSellingPrice, 0, '.', ',') }}</b> Tsh</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
@section('scripts')
@endsection
