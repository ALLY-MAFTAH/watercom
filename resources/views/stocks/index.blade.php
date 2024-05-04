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
                                <b>{{ __('STOCK') . ' - ' }}</b>
                                <div class="btn btn-icon round counter" style="">
                                    {{ $stocks->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                @if (Auth::user()->role_id == 1)
                    <div class="col text-right">
                        <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo">

                            <i class="feather icon-plus"></i> Add New Product to Stock

                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('stocks.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="input-group">
                                        <input id="name" type="text" placeholder=""
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="type" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Type</option>
                                            <option value="Water">Water</option>
                                            <option value="Soda">Soda</option>
                                        </select>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-1">
                                    <label for="volume" class=" col-form-label text-sm-start">{{ __('Volume') }}</label>
                                    <div class="input-group">
                                        <input id="volume" type="number" step="any" placeholder="00"
                                            class="form-control @error('volume') is-invalid @enderror" name="volume"
                                            value="{{ old('volume') }}" required autocomplete="volume" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="measure" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Measure</option>
                                            <option value="Litres">Litres</option>
                                            <option value="Millilitres">Millilitres</option>
                                        </select>
                                        @error('volume')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-1">
                                    <label for="quantity" class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                    <div class="input-group">
                                        <input id="quantity" type="number" step="any" placeholder="00"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity') }}" required autocomplete="quantity" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Unit</option>
                                            <option value="Bottles">Bottles</option>
                                            <option value="Cartons">Cartons</option>
                                        </select>
                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-lg-4 mb-1">
                                    <label for="cost"
                                        class=" col-form-label text-sm-start">{{ __('Buying Price') }}</label>
                                    <div class="">
                                        <input id="cost" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('cost') is-invalid @enderror" name="cost"
                                            value="{{ old('cost') }}"required autocomplete="cost" autofocus>
                                        @error('cost')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-1">
                                    <label for="price"
                                        class=" col-form-label text-sm-start">{{ __('Selling Price') }}</label>
                                    <div class="">
                                        <input id="price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value="{{ old('price') }}"required autocomplete="price" autofocus>
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-1">
                                    <label for="special_price"
                                        class=" col-form-label text-sm-start">{{ __('Special Selling Price') }}</label>
                                    <div class="">
                                        <input id="special_price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('special_price') is-invalid @enderror" name="special_price"
                                            value="0"required autocomplete="price" autofocus>
                                        @error('special_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="row mb-1 mt-2">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <table id="data-tebo1"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Name</th>
                    {{-- <th>Type</th> --}}
                    <th>Volume</th>
                    <th>Quantity</th>
                    @if (Auth::user()->role_id == 1)
                        <th class="text-right">Buying Price</th>
                    @endif
                    <th class="text-right">Selling Price</th>
                    <th class="text-right">Special Price</th>
                    <th class="text-right">Refill Price</th>
                    <th>Last Updated</th>
                    @if (Auth::user()->role_id == 1)
                        <th class="text-center">Discount</th>
                        <th class="text-center">Status</th>
                    @endif
                    <th class="text-center">Actions</th>
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
                            {{-- <td>{{ $stock->type }}</td> --}}
                            <td>{{ $stock->volume }} {{ $stock->measure }}</td>
                            <td>{{ $stock->quantity . ' ' . $stock->unit }}</td>
                            @if (Auth::user()->role_id == 1)
                                <td class="text-end">{{ number_format($stock->cost, 0, '.', ',') }} Tsh</td>
                            @endif
                            @php
                                $totalBuyingPrice = $totalBuyingPrice + $stock->cost * $stock->quantity;
                                $totalSellingPrice = $totalSellingPrice + $stock->product->price * $stock->quantity;
                            @endphp
                            <td class="text-end">{{ number_format($stock->product->price, 0, '.', ',') }} Tsh</td>
                            <td class="text-end">{{ number_format($stock->product->special_price, 0, '.', ',') }} Tsh</td>
                            <td class="text-end">{{ number_format($stock->product->refill_price, 0, '.', ',') }} Tsh</td>
                            <td class="">{{ $stock->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                            @if (Auth::user()->role_id == 1)
                                <td class="text-center">
                                    <form id="toggle-status-form-{{ $stock->product->id }}" method="POST"
                                        class="px-2" action="{{ route('products.toggle-discount', $stock->product) }}">
                                        <div class="form-check form-switch ">
                                            <input type="hidden" name="has_discount" value="0">
                                            <input type="checkbox" name="has_discount"
                                                id="status-switch-{{ $stock->product->id }}" class="form-check-input "
                                                @if ($stock->product->has_discount) checked @endif
                                                @if ($stock->trashed()) disabled @endif value="1"
                                                onclick="this.form.submit()" />
                                        </div>
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form id="toggle-status-form-{{ $stock->id }}" method="POST" class="px-2"
                                        action="{{ route('stocks.toggle-status', $stock) }}">
                                        <div class="form-check form-switch ">
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" id="status-switch-{{ $stock->id }}"
                                                class="form-check-input " @if ($stock->status) checked @endif
                                                @if ($stock->trashed()) disabled @endif value="1"
                                                onclick="this.form.submit()" />
                                        </div>
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </td>
                            @endif
                            <td class="text-center">
                                {{-- <a href="{{ route('stocks.show', $stock) }}"
                                    class="btn btn-sm btn-outline-info collapsed mx-2" type="button">
                                    <i class="feather icon-edit"></i> View
                                </a> --}}
                                @if (Auth::user()->role_id == 1)
                                    <a href="#" class="btn btn-sm btn-outline-primary collapsed mx-2"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#editModal-{{ $stock->id }}" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        <i class="feather icon-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger mx-2"
                                        onclick="if(confirm('Are you sure want to delete {{ $stock->name }}?')) document.getElementById('delete-stock-{{ $stock->id }}').submit()">
                                        <i class="f"></i>Delete
                                    </a>
                                    <form id="delete-stock-{{ $stock->id }}" method="post"
                                        action="{{ route('stocks.delete', $stock) }}">@csrf @method('delete')
                                    </form>
                                @endif
                                <div class="modal modal-md fade" id="editModal-{{ $stock->id }}"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Product in Stock
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('stocks.edit', $stock) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="text-start mb-1">
                                                        <label for="name"
                                                            class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                                        <div class="input-group">
                                                            <input id="name" type="text" placeholder=""
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                name="name"
                                                                value="{{ old('name', $stock->name) }}" required
                                                                autocomplete="name" autofocus style="float: left;">
                                                            <select class="form-control form-select" name="type"
                                                                required
                                                                style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                <option value="Water"
                                                                    {{ $stock->type == 'Water' ? 'selected' : '' }}>
                                                                    Water</option>
                                                                <option value="Soda"
                                                                    {{ $stock->type == 'Soda' ? 'selected' : '' }}>
                                                                    Soda</option>
                                                            </select>
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="volume"
                                                            class=" col-form-label text-sm-start">{{ __('Volume') }}</label>
                                                        <div class="input-group">
                                                            <input id="volume" type="number" step="any"
                                                                placeholder="00"
                                                                class="form-control @error('volume') is-invalid @enderror"
                                                                name="volume"
                                                                value="{{ old('volume', $stock->volume) }}" required
                                                                autocomplete="volume" autofocus style="float: left;">
                                                            <select class="form-control form-select" name="measure"
                                                                required
                                                                style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                <option value="Litres"
                                                                    {{ $stock->measure == 'Litres' ? 'selected' : '' }}>
                                                                    Litres</option>
                                                                <option value="Millilitres"
                                                                    {{ $stock->measure == 'Millilitres' ? 'selected' : '' }}>
                                                                    Millilitres</option>
                                                            </select>
                                                            @error('volume')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="quantity"
                                                            class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                                        <div class="input-group">
                                                            <input id="quantity" type="number" step="any"
                                                                placeholder="00"
                                                                class="form-control @error('quantity') is-invalid @enderror"
                                                                name="quantity"
                                                                value="{{ old('quantity', $stock->quantity) }}"
                                                                required autocomplete="quantity" autofocus
                                                                style="float: left;">
                                                            <select class="form-control form-select" name="unit"
                                                                required
                                                                style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                <option value="Bottles"
                                                                    {{ $stock->unit == 'Bottles' ? 'selected' : '' }}>
                                                                    Bottles</option>
                                                                <option value="Cartons"
                                                                    {{ $stock->unit == 'Cartons' ? 'selected' : '' }}>
                                                                    Cartons</option>
                                                            </select>
                                                            @error('quantity')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="cost"
                                                            class="col-form-label text-sm-start">{{ __('Buying Price') }}</label>
                                                        <input id="cost" type="number" placeholder="Tsh"
                                                            class="form-control @error('cost') is-invalid @enderror"
                                                            name="cost" value="{{ old('cost', $stock->cost) }}"
                                                            required autocomplete="cost" autofocus>
                                                        @error('cost')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="price"
                                                            class="col-form-label text-sm-start">{{ __('Selling Price') }}</label>
                                                        <input id="price" type="number" placeholder="Tsh"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            name="price"
                                                            value="{{ old('price', $stock->product->price) }}"
                                                            required autocomplete="price" autofocus>
                                                        @error('price')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="special_price"
                                                            class="col-form-label text-sm-start">{{ __('Special Selling Price') }}</label>
                                                        <input id="special_price" type="number" placeholder="Tsh"
                                                            class="form-control @error('special_price') is-invalid @enderror"
                                                            name="special_price"
                                                            value="{{ old('special_price', $stock->product->special_price) }}"
                                                            required autocomplete="price" autofocus>
                                                        @error('special_price')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
@if ($stock->volume==19 )

<div class="text-start mb-1">
    <label for="refill_price"
        class="col-form-label text-sm-start">{{ __('Refill Selling Price') }}</label>
    <input id="refill_price" type="number" placeholder="Tsh"
        class="form-control @error('refill_price') is-invalid @enderror"
        name="refill_price"
        value="{{ old('refill_price', $stock->product->refill_price) }}"
        required autocomplete="price" autofocus>
    @error('refill_price')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
@endif
                                                    <div class="row mb-1 mt-2">
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
                            </td>

                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td colspan="3"></td>
                    <td>
                        <h5>Total</h5>
                    </td>
                    <td class="text-right">
                        <h5>{{ number_format($totalBuyingPrice, 0, '.', ',') }} Tsh</h5>
                    </td>
                    <td class="text-right">
                        <h5>{{ number_format($totalSellingPrice, 0, '.', ',') }} Tsh</h5>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
