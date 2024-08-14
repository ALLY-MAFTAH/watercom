@extends('layouts.app')
@section('title')
    Stock
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <a href="{{ route('stocks.index') }}" style="text-decoration: none;font-size:15px">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
                @if (Auth::user()->role_id == 1)
                    <div class="col text-right">
                        <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo">
                            <i class="feather icon-plus"></i> Edit Product

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
                        <form method="POST" action="{{ route('stocks.edit', $stock) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-2 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="input-group">
                                        <input id="name" type="text" placeholder=""
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $stock->name) }}" required autocomplete="name" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="type" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="Water" {{ $stock->type == 'Water' ? 'selected' : '' }}>
                                                Water</option>
                                            <option value="Soda" {{ $stock->type == 'Soda' ? 'selected' : '' }}>
                                                Soda</option>
                                        </select>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-1">
                                    <label for="volume" class=" col-form-label text-sm-start">{{ __('Volume') }}</label>
                                    <div class="input-group">
                                        <input id="volume" type="number" step="any" placeholder="00"
                                            class="form-control @error('volume') is-invalid @enderror" name="volume"
                                            value="{{ old('volume', $stock->volume) }}" required autocomplete="volume"
                                            autofocus style="float: left;">
                                        <select class="form-control form-select" name="measure" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="Litres" {{ $stock->measure == 'Litres' ? 'selected' : '' }}>
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
                                <div class="col-lg-2 mb-1">
                                    <label for="quantity"
                                        class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                    <div class="input-group">
                                        <input id="quantity" type="number" step="any" placeholder="00"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity', $stock->quantity) }}" required
                                            autocomplete="quantity" autofocus style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="Bottles" {{ $stock->unit == 'Bottles' ? 'selected' : '' }}>
                                                Bottles</option>
                                            <option value="Cartons" {{ $stock->unit == 'Cartons' ? 'selected' : '' }}>
                                                Cartons</option>
                                        </select>
                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-1">
                                    <label for="cost"
                                        class=" col-form-label text-sm-start">{{ __('Cost (Tsh)') }}</label>
                                    <div class="">
                                        <input id="cost" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('cost') is-invalid @enderror" name="cost"
                                            value="{{ old('cost', $stock->cost) }}"required autocomplete="cost" autofocus>
                                        @error('cost')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-1">
                                    <label for="price"
                                        class=" col-form-label text-sm-start">{{ __('Selling Price (Tsh)') }}</label>
                                    <div class="">
                                        <input id="price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value="{{ old('price', $stock->product->price) }}"required
                                            autocomplete="price" autofocus>
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-1">
                                    <label for="special_price"
                                        class=" col-form-label text-sm-start">{{ __('Special Price (Tsh)') }}</label>
                                    <div class="">
                                        <input id="special_price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('special_price') is-invalid @enderror" name="special_price"
                                            value="{{ old('special_price', $stock->product->special_price) }}"required
                                            autocomplete="price" autofocus>
                                        @error('special_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-1">
                                    <label for="refill_price"
                                        class=" col-form-label text-sm-start">{{ __('Refill Price (Tsh)') }}</label>
                                    <div class="">
                                        <input id="refill_price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('refill_price') is-invalid @enderror" name="refill_price"
                                            value="{{ old('refill_price', $stock->product->refill_price) }}"required
                                            autocomplete="price" autofocus>
                                        @error('refill_price')
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
                                        {{ __('Submit') }}
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
                        <div class="col-8">{{ $stock->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Volume:</b> </div>
                        <div class="col-8">{{ $stock->volume . ' ' . $stock->measure }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Quantity:</b> </div>
                        <div class="col-8">{{ $stock->quantity . ' ' . $stock->unit }}</div>
                    </div>
                    @if (Auth::user()->role_id == 1)
                    <div class="row">
                        <div class="col-4"><b> Buying Price:</b> </div>
                        <div class="col-8">{{ number_format($stock->cost, 0, '.', ',') }} Tsh</div>
                    </div>
                    @endif
                        <div class="row">
                            <div class="col-4"><b> Selling Price:</b> </div>
                            <div class="col-8">{{ number_format($stock->product->price, 0, '.', ',') }} Tsh</div>
                        </div>
                        <div class="row">
                            <div class="col-4"><b> Special Price:</b> </div>
                            <div class="col-8">{{ number_format($stock->product->special_price, 0, '.', ',') }} Tsh</div>
                        </div>
                        <div class="row">
                            <div class="col-4"><b> Refill Price:</b> </div>
                            <div class="col-8">{{ number_format($stock->product->refill_price, 0, '.', ',') }} Tsh</div>
                        </div>
                    <div class="row">
                        <div class="col-4"><b> Created:</b> </div>
                        <div class="col-8">{{ $stock->created_at->format('D, d M Y \a\t H:i') }}</div>
                    </div>
                </div>
            </div><br>
            <div class="card">
                <div class="card-header">Utilization Records</div>
                <div class="card-body">
                    <table id="data-tebo1"
                        class=" dt-responsive nowrap table shadow rounded-3 table-responsive table-striped">
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
