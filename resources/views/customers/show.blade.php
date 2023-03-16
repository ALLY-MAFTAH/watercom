@extends('layouts.app')
@section('title')
    Customer
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <a href="{{ route('customers.index') }}" style="text-decoration: none;font-size:15px">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">

                        <i class="feather icon-plus"></i> Edit Customer

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('customers.edit', $customer) }}">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="">
                                        <input id="name" type="text" placeholder=""
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $customer->name) }}" required autocomplete="name"
                                            autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <label for="phone"
                                        class=" col-form-label text-sm-start">{{ __('Mobile Number') }}</label>
                                    <div class="">
                                        <input id="phone" type="text" placeholder="Eg; 0712345678"
                                        pattern="0[0-9]{9}" maxlength="10"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone', $customer->phone) }}" required autocomplete="phone"
                                            autofocus>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1 mt-2">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-4"><b> Name:</b> </div>
                        <div class="col-8">{{ $customer->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Mobile:</b> </div>
                        <div class="col-8">{{ $customer->phone }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Customer Since:</b> </div>
                        <div class="col-8">{{ Illuminate\Support\Carbon::parse($customer->created_at)->format('d M Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-7">

                </div>
            </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header"><h5>Purchases</h5></div>
                <div class="card-body">
                    <table id="data-tebo1"
                        class=" dt-responsive nowrap table shadow rounded-3 table-responsive table-striped">
                        <thead class="shadow rounded-3">
                            <th>#</th>
                            <th>Date</th>
                            <th>Bought Products </th>
                            <th class="text-right">Total Paid Amount</th>
                            <th>Seller</th>
                        </thead>
                        <tbody>
                            @php
                                $total=0;
                            @endphp
                            @foreach ($customer->goods as $index => $good)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ Illuminate\Support\Carbon::parse($good->date)->format('d M Y') }}</td>
                                    <td>
                                        @foreach ($good->purchases as $purchase)
                                            <div>
                                                {{ $purchase->name }} {{ $purchase->volume }} {{ $purchase->measure }} -
                                                {{ $purchase->quantity }} {{ $purchase->unit }},
                                            </div>
                                        @endforeach

                                    </td>
                                    @php
                                        $total=$total+$good->amount_paid;
                                    @endphp
                                    <td class="text-right">{{ number_format($good->amount_paid, 0, '.', ',') }} Tsh</td>
                                    <td>{{ $good->seller }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <td colspan="2"></td>
                            <td  class="text-right"><h5>Total</h5></td>

                            <td class="text-right"><h5>{{ number_format($total, 0, '.', ',') }} Tsh</h5></td>
                        </tr>
                    </table>
                </div>
            </div>

@endsection
@section('scripts')
@endsection
