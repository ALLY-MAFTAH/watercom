@extends('layouts.app')

@section('title')
    Report | {!! config('app.name') !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header text-center">
            <h5>REPORTS LIST</h5>
        </div>

        <div class="card-body">
            <br>
            <br>
            <div class="row">
                <div class="col text-center">
                    <a href="#" class="btn btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#salesReportForm" aria-expanded="false" aria-controls="reportForm">Sales
                        Report</a>
                </div>
                <div class="col text-center">
                    <a href="#" class="btn btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#stockReportForm" aria-expanded="false" aria-controls="reportForm">Stock
                        Report</a>
                </div>
               
            </div>
            <br>
            <div id="salesReportForm" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="salesReportForm" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="GET" action="{{ route('reports.sales') }}">
                            @csrf
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="from_date" class=" col-form-label text-sm-start">{{ __('From') }}</label>
                                    <div class="">
                                        <input id="from_date" type="date"
                                            class="form-control @error('from_date') is-invalid @enderror" name="from_date"
                                            value="{{ old('from_date') }}"required autocomplete="from_date" autofocus>
                                        @error('from_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col mb-1">
                                    <label for="to_date" class=" col-form-label text-sm-start">{{ __('To') }}</label>
                                    <div class="">
                                        <input id="to_date" type="date"
                                            class="form-control @error('to_date') is-invalid @enderror" name="to_date"
                                            value="{{ old('to_date') }}"required autocomplete="to_date" autofocus>
                                        @error('to_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col mb-1">
                                    <label for="orientation"
                                        class=" col-form-label text-sm-start">{{ __('Orientation') }}</label>
                                    <div class="input-group">
                                        <select id="orientation" class="form-control form-select" name="orientation"
                                            required>
                                            <option value="landscape">Landscape</option>
                                            <option value="potrait">Potrait</option>
                                        </select>
                                        @error('orientation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col mb-1">
                                    <label for="based_on"
                                        class=" col-form-label text-sm-start">{{ __('View Based On') }}</label>
                                    <div class="input-group">
                                        <select id="based_on" class="form-control form-select" name="based_on" required>
                                            <option value="Product">Products</option>
                                            <option value="Customer">Customers</option>
                                        </select>
                                        @error('based_on')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="col mb-1" style="">
                                    <label for="customer_id"
                                        class=" col-form-label text-sm-start">{{ __('Customer') }}</label>
                                    <div class="input-group">
                                        <select id="customer_id" class="form-control form-select" name="customer_id" required>
                                            <option value="0">All Customers</option>
                                            @foreach ($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>

                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col mb-1" id="sort_by_field" style="display: block">
                                    <label for="sort_by"
                                        class=" col-form-label text-sm-start">{{ __('Sort By') }}</label>
                                    <div class="input-group">
                                        <select id="sort_by" class="form-control form-select" name="sort_by" required>
                                            <option value="">--</option>

                                        </select>
                                        @error('sort_by')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row mb-1 my-4">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary">
                                        {{ __('Generate Report') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div id="stockReportForm" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="GET" action="{{ route('reports.stocks') }}">
                            @csrf
                            <div class="row">
                                {{-- <div class="col mb-1">
                                    <label for="from_date"
                                        class=" col-form-label text-sm-start">{{ __('From') }}</label>
                                    <div class="">
                                        <input id="from_date" type="date"
                                            class="form-control @error('from_date') is-invalid @enderror" name="from_date"
                                            value="{{ old('from_date') }}"required autocomplete="from_date" autofocus>
                                        @error('from_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col mb-1">
                                    <label for="to_date"
                                        class=" col-form-label text-sm-start">{{ __('To') }}</label>
                                    <div class="">
                                        <input id="to_date" type="date"
                                            class="form-control @error('to_date') is-invalid @enderror" name="to_date"
                                            value="{{ old('to_date') }}"required autocomplete="to_date" autofocus>
                                        @error('to_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="col mb-1">
                                    <label for="orientation"
                                        class=" col-form-label text-sm-start">{{ __('Orientation') }}</label>
                                    <div class="input-group">
                                        <select id="orientation" class="form-control form-select" name="orientation"
                                            required>
                                            <option value="landscape">Landscape</option>
                                            <option value="potrait">Potrait</option>
                                        </select>
                                        @error('orientation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col mb-1" style="">
                                    <label for="stock_id"
                                        class=" col-form-label text-sm-start">{{ __('Product') }}</label>
                                    <div class="input-group">
                                        <select id="stock_id" class="form-control form-select" name="stock_id" required>
                                            <option value="0">All Products</option>
                                            @foreach ($stocks as $stock)
                                                <option value="{{ $stock->id }}">
                                                    {{ $stock->name . '-' . $stock->volume . ' ' . $stock->measure }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('stock_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col mb-1" id="sort_by_field" style="display: block">
                                    <label for="sort_by"
                                        class=" col-form-label text-sm-start">{{ __('Sort By') }}</label>
                                    <div class="input-group">
                                        <select id="sort_by" class="form-control form-select" name="sort_by" required>
                                            <option value="name">Name</option>
                                            <option value="type">Type</option>

                                        </select>
                                        @error('sort_by')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1 my-4">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-primary">
                                        {{ __('Generate Report') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const basedOnSelect = document.getElementById('based_on');
        const sortBySelect = document.getElementById('sort_by');
        const groupBySelect = document.getElementById('group_by');

        basedOnSelect.addEventListener('change', () => {
            if (basedOnSelect.value === 'Product') {
                sortBySelect.innerHTML = `
                <option value="seller">Cashier Name</option>
                <option value="name">Product Name</option>
                <option value="quantity">Quantity</option>
                <option value="price">Price</option>
                <option value="date">Issued Date</option>
                <option value="type">Soda/Water</option>
            `;
                groupBySelect.innerHTML = `
                <option value="seller">Cashier Name</option>
                <option value="name">Product Name</option>
                <option value="date">Issued Date</option>
                <option value="type">Soda/Water</option>
            `;
            } else if (basedOnSelect.value === 'Customer') {
                sortBySelect.innerHTML = `
                <option value="date">Issued Date</option>
                <option value="amount_paid">Price</option>
                <option value="seller">Cashier Name</option>
                <option value="customer_id">Customer Name</option>
                `;
                groupBySelect.innerHTML = `
                <option value="date">Issued Date</option>
                <option value="seller">Cashier Name</option>
                <option value="customer_id">Customer Name</option>
            `;
            }
        });
        const sortByField = document.getElementById('sort_by_field');

        groupBySelect.addEventListener('change', () => {
            if (groupBySelect.value != "") {
                sortByField.style.display = "none";
            } else {

                sortByField.style.display = "block";
            }
        });
    </script>
@endsection
