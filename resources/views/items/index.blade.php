@extends('layouts.app')
@section('title')
    Items
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
                                <b>{{ __('ITEMS') .' - '}}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $items->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="feather icon-plus"></i> Add Item
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse "
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('items.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="">
                                        <input id="name" type="text" placeholder=""
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="quantity" class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                    <div class="input-group">
                                        <input id="quantity" type="number" step="any" placeholder="00"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity') }}" required autocomplete="quantity" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:120px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Unit</option>
                                            <option value="Kilograms">Kilograms</option>
                                            <option value="Litres">Litres</option>
                                            <option value="Counts">Counts</option>
                                        </select>
                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="cost" class=" col-form-label text-sm-start">{{ __('Cost') }}</label>
                                    <div class="">
                                        <input id="cost" type="number" placeholder="Tsh"
                                            class="form-control @error('cost') is-invalid @enderror" name="cost"
                                            value="{{ old('cost') }}" required autocomplete="cost" autofocus>
                                        @error('cost')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-1">
                                    <label for="ingredient"
                                        class=" col-form-label text-sm-start"><b>{{ __('Ingredients') }}</b></label>
                                    <div
                                        class="input-group input-group control-group after-add-more"style="margin-bottom:10px">
                                        <select id="ingredient_name" class="form-control form-select"
                                            name="ids[]" required style="float: left; width: inaitial; ">
                                            <option value="">{{ 'Name' }}</option>
                                            @foreach ($stocks as $stock)
                                                <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                                            @endforeach
                                            @error('ingredient_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </select>
                                        <input id="ingredient_quantity" type="number" step="any" placeholder="Quantity"
                                            class="form-control @error('ingredient_quantity') is-invalid @enderror"
                                            name="quantities[]" value="{{ old('ingredient_quantity') }}" required
                                            autocomplete="ingredient_quantity" autofocus style="float: left;">
                                        @error('ingredient_quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <select id="ingredient_unit" class="form-control form-select"
                                            name="units[]" required
                                            style="float: left; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Unit</option>
                                            <option value="Kilograms">Kilograms</option>
                                            <option value="Litres">Litres</option>
                                            <option value="Counts">Counts</option>
                                        </select>
                                        @error('ingredient_unit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <span class="input-group-btn">
                                            <button class="btn btn-outline-success add-more"
                                                type="button"style="border-top-left-radius: 0%;border-bottom-left-radius: 0%"><i
                                                    class="bx bx-plus"></i></button>
                                        </span>
                                    </div>
                                </div>

                                <!-- Copy Fields -->
                                <div class="copy hide">
                                    <div class="input-group control-group" style="margin-bottom:10px">
                                        <select id="ingredient_name" class="form-control form-select"
                                            name="ids[]" required style="float: left; width: inaitial; ">
                                            <option value="">{{ 'Name' }}</option>
                                            @foreach ($stocks as $stock)
                                                <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                                            @endforeach
                                            @error('ingredient_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </select>
                                        <input id="ingredient_quantity" type="number" step="any"
                                            placeholder="Quantity"
                                            class="form-control @error('ingredient_quantity') is-invalid @enderror"
                                            name="quantities[]" value="{{ old('ingredient_quantity') }}"
                                            required autocomplete="ingredient_quantity" autofocus style="float: left;">
                                        @error('ingredient_quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <select id="ingredient_unit" class="form-control form-select"
                                            name="units[]" required
                                            style="float: left; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Unit</option>
                                            <option value="Kilograms">Kilograms</option>
                                            <option value="Litres">Litres</option>
                                            <option value="Counts">Counts</option>
                                        </select>
                                        @error('ingredient_unit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <span class="input-group-btn">
                                            <button class="btn btn-outline-danger remove"
                                                type="button"style="border-top-left-radius: 0%;border-bottom-left-radius: 0%"><i
                                                    class="bx bx-minus"></i></button>
                                        </span>
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
            @if (Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}
            </p>
        @endif
            <table id="data-tebo1"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th class="text-right">Cost (Tsh)</th>
                    <th>Last Updated</th>
                    <th style="max-width: 50px">Status</th>
                    <th></th>
                    <th></th>
                    <th></th>

                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity . ' ' . $item->unit }}</td>
                            <td class="text-right">{{ number_format($item->cost, 0, '.', ',') }} </td>
                            <td class="">{{ $item->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td class="text-center">
                                <form id="toggle-status-form-{{ $item->id }}" method="POST"
                                    action="{{ route('items.toggle-status', $item) }}">
                                    <div class="form-check form-switch ">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status-switch-{{ $item->id }}"
                                            class="form-check-input " @if ($item->status) checked @endif
                                            @if ($item->trashed()) disabled @endif value="1"
                                            onclick="this.form.submit()" />
                                    </div>
                                    @csrf
                                    @method('PUT')
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-outline-info collapsed"
                                    type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $item->id }}"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="feather icon-edit"></i> Edit
                                </a>
                                <div class="modal modal-sm fade" id="editModal-{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('items.edit', $item) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="text-start mb-1">
                                                        <label for="name"
                                                            class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                                        <input id="name" type="text" placeholder=""
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name', $item->name) }}"
                                                            required autocomplete="name" autofocus>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="quantity"
                                                            class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                                        <div class="input-group">
                                                            <input id="quantity" type="number" step="any"
                                                                placeholder="00"
                                                                class="form-control @error('quantity') is-invalid @enderror"
                                                                name="quantity"
                                                                value="{{ old('quantity', $item->quantity) }}" required
                                                                autocomplete="quantity" autofocus style="float: left;">
                                                            <select class="form-control form-select" name="unit"
                                                                required
                                                                style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                <option value="Kilograms"
                                                                    {{ $item->unit == 'Kilograms' ? 'selected' : '' }}>
                                                                    Kilograms</option>
                                                                <option value="Litres"
                                                                    {{ $item->unit == 'Litres' ? 'selected' : '' }}>
                                                                    Litres</option>
                                                                <option value="Counts"
                                                                    {{ $item->unit == 'Counts' ? 'selected' : '' }}>
                                                                    Counts</option>
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
                                                            class="col-form-label text-sm-start">{{ __('Cost') }}</label>
                                                        <input id="cost" type="number" placeholder="Tsh"
                                                            class="form-control @error('cost', $item->cost) is-invalid @enderror"
                                                            name="cost" value="{{ old('cost', $item->cost) }}"
                                                            required autocomplete="cost" autofocus>
                                                        @error('cost')
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
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-danger"
                                    onclick="if(confirm('Are you sure want to delete {{ $item->name }}?')) document.getElementById('delete-item-{{ $item->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-item-{{ $item->id }}" method="post"
                                    action="{{ route('items.delete', $item) }}">@csrf @method('delete')
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
