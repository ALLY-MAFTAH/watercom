@extends('layouts.app')
@section('title')
    Product
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <a href="{{ route('products.index') }}" style="text-decoration: none;font-size:15px">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

                        <i class="feather icon-plus"></i> Edit Product

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('products.edit', $product) }}">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-sm-3 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <select id="name" class="form-control form-select" name="item_id" required
                                        style="float: left; width: inaitial; ">
                                        <option value="">{{ 'Name' }}</option>
                                        @foreach ($items as $item)

                                        <option  value="{{ $item->id }}" {{ $item->id == $product->item_id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
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
                                            value="{{ old('container', $product->container) }}" required
                                            autocomplete="container" autofocus>
                                        @error('container')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-1">
                                    <label for="quantity"
                                        class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                    <div class="input-group">
                                        <input id="quantity" type="number" step="any" placeholder="00"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity', $product->quantity) }}" required
                                            autocomplete="quantity" autofocus style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:120px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="Kilograms"{{ $product->unit == 'Kilograms' ? 'selected' : '' }}>
                                                Kilograms</option>
                                            <option value="Litres"{{ $product->unit == 'Litres' ? 'selected' : '' }}>Litres
                                            </option>
                                            <option value="Counts"{{ $product->unit == 'Counts' ? 'selected' : '' }}>Counts
                                            </option>
                                        </select>
                                        @error('quantity')
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
                                        value="{{ old('price', $product->price) }}" required autocomplete="price" autofocus>
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                        <div class="col-8">{{ $product->item->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Quantity:</b> </div>
                        <div class="col-8">{{ $product->quantity . ' ' . $product->unit }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Container:</b> </div>
                        <div class="col-8">{{ $product->container }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Price:</b> </div>
                        <div class="col-8">{{ number_format($product->price, 0, '.', ',') }} Tsh</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Created:</b> </div>
                        <div class="col-8">{{ $product->created_at->format('D, d M Y \a\t H:i') }}</div>
                    </div>
                </div>

            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header">Sales Records</div>
            <div class="card-body">
                <table id="data-tebo1" class=" dt-responsive nowrap table shadow rounded-3 table-responsive table-striped">
                    <thead class="shadow rounded-3">
                        <th>#</th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Seller</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
@endsection
