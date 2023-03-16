@extends('layouts.app')
@section('title')
    Products
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
                                <b>{{ __('PRODUCTS') . ' - ' }}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $products->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" hidden class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">
                        <i class="feather icon-plus"></i> Add Product
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse "
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('products.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <select id="name" class="form-control form-select" name="stock_id" required
                                        style="float: left; width: inaitial; ">
                                        <option value="">{{ 'Name' }}</option>
                                        @foreach ($stocks as $stock)
                                            <option value="{{ $stock->id }}">{{ $stock->name.' '.$stock->volume.' '.$stock->measure }}</option>
                                        @endforeach
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </select>
                                </div>
                                <div class="col-sm-3 mb-1">
                                    <label for="container"
                                        class=" col-form-label text-sm-start">{{ __('Container') }}</label>
                                    <div class="">
                                        <input id="container" type="text" placeholder=""
                                            class="form-control @error('container') is-invalid @enderror" name="container"
                                            value="{{ old('container') }}" required autocomplete="container" autofocus>
                                        @error('container')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-1">
                                    <label for="volume"
                                        class=" col-form-label text-sm-start">{{ __('Volume') }}</label>
                                    <div class="input-group">
                                        <input id="volume" type="number" step="any" placeholder="00"
                                            class="form-control @error('volume') is-invalid @enderror" name="volume"
                                            value="{{ old('volume') }}" required autocomplete="volume" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:120px; width: inaitial; background-color:rgb(238, 238, 242)">
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
                                <div class="col-sm-3 mb-1">
                                    <label for="price" class=" col-form-label text-sm-start">{{ __('Price') }}</label>
                                    <input id="price" type="number" placeholder="Tsh"
                                        class="form-control @error('price') is-invalid @enderror" name="price"
                                        value="{{ old('price') }}" required autocomplete="price" autofocus>
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-1 mt-2">
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
                    <th>Type</th>
                    <th>Name</th>
                    <th>Volume</th>
                    <th>Unit</th>
                    <th class="text-right">Price (Tsh)</th>
                    <th>Last Updated</th>
                    <th style="max-width: 50px">Status</th>
                    <th hidden></th>
                    <th hidden></th>
                    <th hidden></th>

                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $product->type }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->volume. ' ' . $product->measure  }}</td>
                            <td>{{ $product->unit }}</td>
                            <td class="text-right">{{ number_format($product->price, 0, '.', ',') }} </td>
                            <td class="">{{ $product->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td class="text-center">
                                <form id="toggle-status-form-{{ $product->id }}" method="POST"
                                    action="{{ route('products.toggle-status', $product) }}">
                                    <div class="form-check form-switch ">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status-switch-{{ $product->id }}"
                                            class="form-check-input " @if ($product->status) checked @endif
                                            @if ($product->trashed()) disabled @endif value="1"
                                            onclick="this.form.submit()" />
                                    </div>
                                    @csrf
                                    @method('PUT')
                                </form>
                            </td>
                            <td hidden>
                                <a href="{{ route('products.show', $product) }}"
                                    class="btn btn-sm btn-outline-info collapsed" type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                            </td>
                            <td hidden class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $product->id }}"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="feather icon-edit"></i> Edit
                                </a>
                                <div class="modal modal-sm fade" id="editModal-{{ $product->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('products.edit', $product) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="text-start mb-1">
                                                        <label for="name"
                                                            class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                                        <select id="name" class="form-control form-select"
                                                            name="stock_id" required autocomplete="container" autofocus>
                                                            @foreach ($stocks as $stock)
                                                                <option
                                                                    value="{{ $stock->id }}"{{ $product->stock_id == $stock->id ? 'selected' : '' }}>
                                                                    {{ $stock->name }}</option>
                                                            @endforeach
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </select>
                                                    </div>

                                                    <div class="text-start mb-1">
                                                        <label for="volume"
                                                            class=" col-form-label text-sm-start">{{ __('Volume') }}</label>
                                                        <div class="input-group">
                                                            <input id="volume" type="number" step="any"
                                                                placeholder="00"
                                                                class="form-control @error('volume') is-invalid @enderror"
                                                                name="volume"
                                                                value="{{ old('volume', $product->volume) }}" required
                                                                autocomplete="volume" autofocus style="float: left;">
                                                            <select class="form-control form-select" name="measure"
                                                                required
                                                                style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                <option value="Litres"
                                                                    {{ $product->measure == 'Litres' ? 'selected' : '' }}>
                                                                    Litres</option>
                                                                <option value="Millilitres"
                                                                    {{ $product->measure == 'Millilitres' ? 'selected' : '' }}>
                                                                    Millilitres</option>
                                                            </select>
                                                            @error('quantity')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="price"
                                                            class="col-form-label text-sm-start">{{ __('Price') }}</label>
                                                        <input id="price" type="number" placeholder="Tsh"
                                                            class="form-control @error('price', $product->price) is-invalid @enderror"
                                                            name="price" value="{{ old('price', $product->price) }}"
                                                            required autocomplete="price" autofocus>
                                                        @error('price')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror

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
                                </div>
                            </td>
                            <td hidden class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-danger"
                                    onclick="if(confirm('Are you sure want to delete {{ $product->name }}?')) document.getElementById('delete-product-{{ $product->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-product-{{ $product->id }}" method="post"
                                    action="{{ route('products.delete', $product) }}">@csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
