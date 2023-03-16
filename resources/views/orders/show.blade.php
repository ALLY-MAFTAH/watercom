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
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

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
                                            value="{{ old('name',$customer->name) }}" required autocomplete="name" autofocus>
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
                                        <input id="phone" type="text" placeholder="+255712121212"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone',$customer->phone) }}" required autocomplete="phone" autofocus>
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
                                    </div>
                <div class="col-7">

                </div>
            </div><br>
            <div class="card">
                <div class="card-header">Sales</div>
                <div class="card-body">
                    <table id="data-tebo1" class=" dt-responsive nowrap table shadow rounded-3 table-responsive table-striped">
                        <thead class="shadow rounded-3">
                            <th>#</th>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Seller</th>
                        </thead>
                        <tbody>
                            @foreach ($customer->sales() as $sale)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
