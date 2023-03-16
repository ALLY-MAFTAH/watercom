@extends('icecream::layouts.master')
@section('title')
    Item
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <a href="{{ route('items.index') }}" style="text-decoration: none;font-size:15px">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#editCollapse" aria-expanded="false"
                        aria-controls="editCollapse">
                        <i class="feather icon-plus"></i> Edit Item
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="editCollapse" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('items.edit', $item) }}">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-sm-4 text-start mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <input id="name" type="text" placeholder=""
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name', $item->name) }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4 text-start mb-1">
                                    <label for="quantity" class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                    <div class="input-group">
                                        <input id="quantity" type="number" step="any" placeholder="00"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity', $item->quantity) }}" required autocomplete="quantity"
                                            autofocus style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="Kilograms" {{ $item->unit == 'Kilograms' ? 'selected' : '' }}>
                                                Kilograms</option>
                                            <option value="Litres" {{ $item->unit == 'Litres' ? 'selected' : '' }}>
                                                Litres</option>
                                            <option value="Counts" {{ $item->unit == 'Counts' ? 'selected' : '' }}>
                                                Counts</option>
                                        </select>
                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 text-start mb-1">
                                    <label for="cost"
                                        class="col-form-label text-sm-start">{{ __('Cost (Tsh)') }}</label>
                                    <input id="cost" type="number" placeholder="Tsh"
                                        class="form-control @error('cost', $item->cost) is-invalid @enderror" name="cost"
                                        value="{{ old('cost', $item->cost) }}" required autocomplete="cost" autofocus>
                                    @error('cost')
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
                        <div class="col-8">{{ $item->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Quantity:</b> </div>
                        <div class="col-8">{{ $item->quantity . ' ' . $item->unit }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Cost:</b> </div>
                        <div class="col-8">{{ number_format($item->cost, 0, '.', ',') }} Tsh</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Created:</b> </div>
                        <div class="col-8">{{ $item->created_at->format('D, d M Y \a\t H:i') }}</div>
                    </div>
                    <div class="row pb-2">
                        <div class="col-4"><b> Ingredients:</b> </div>
                        <div class="col-8" style="background: rgb(234, 232, 244); border-radius:5px">
                            @forelse ($ingredients as $ingredient)
                                <span style="">
                                    {{ $ingredient->quantity . ' ' . $ingredient->unit . ' of ' . $ingredient->stock->name }}
                                </span><br>
                            @empty
                                No Ingredient
                            @endforelse
                            <div class="text-end">
                                @if ($ingredients->count() == 0)
                                    <a id="edit-btn" href="#" style="text-decoration: none;"
                                        onclick="showForm1()">
                                        Add Ingredients
                                    </a>
                                @else
                                    <a id="edit-btn" href="#" style="text-decoration: none;"
                                        onclick="showForm2()">
                                        Change Ingredients
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    @if (Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}
                        </p>
                    @endif
                    <div class="card"id="edit-form" style="display: none">
                        <div class="card-body">
                            @if ($ingredients->count() == 0)
                                <form method="POST" action="{{ route('items.add-ingredients', $item) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col mb-1">
                                            <div
                                                class="input-group input-group control-group after-add-more"style="margin-bottom:10px">
                                                <select id="ingredient_name" class="form-control form-select"
                                                    name="ids[]" required style="float: left; width: inaitial; ">
                                                    <option value="">{{ 'Name' }}</option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}">{{ $stock->name }}
                                                        </option>
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
                                                        <option value="{{ $stock->id }}">{{ $stock->name }}
                                                        </option>
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
                            @else
                                <form id="update-ingredients" method="POST"
                                    action="{{ route('items.edit-ingredients', $item) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        @foreach ($ingredients as $ingredient)
                                            <div class="input-group control-group" style="margin-bottom:10px">
                                                <input hidden name="ingredient_id[]" value="{{ $ingredient->id }}"
                                                    type="text">
                                                <select disabled id="ingredient_name" class="form-control forsm-select"
                                                    name="ids[]" required style="float: left; width: inaitial; ">
                                                    @foreach ($stocks as $stock)
                                                        <option
                                                            value="{{ $stock->id }}"{{ $ingredient->stock_id == $stock->id ? 'selected' : '' }}>
                                                            {{ $stock->name }}</option>
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
                                                    name="quantities[]"
                                                    value="{{ old('ingredient_quantity', $ingredient->quantity) }}"
                                                    required autocomplete="ingredient_quantity" autofocus
                                                    style="float: left;">
                                                @error('ingredient_quantity')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <select id="ingredient_unit" class="form-control form-select"
                                                    name="units[]" required
                                                    style="float: left; width: inaitial; background-color:rgb(238, 238, 242)">
                                                    <option
                                                        value="Kilograms"{{ $ingredient->unit == 'Kilograms' ? 'selected' : '' }}>
                                                        Kilograms</option>
                                                    <option
                                                        value="Litres"{{ $ingredient->unit == 'Litres' ? 'selected' : '' }}>
                                                        Litres
                                                    </option>
                                                    <option
                                                        value="Counts"{{ $ingredient->unit == 'Counts' ? 'selected' : '' }}>
                                                        Counts
                                                    </option>
                                                </select>
                                                @error('ingredient_unit')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <span class="input-group-btn">
                                                    <button class="btn btn-outline-danger"
                                                        type="button"style="border-top-left-radius: 0%;border-bottom-left-radius: 0%"
                                                        onclick="if(confirm('{{ $ingredient->stock->name }} will be deleted from this item\'s ingredients.')) document.getElementById('delete-ingredient-{{ $ingredient->id }}').submit()">
                                                        <i class="bx bx-trash"></i></button>

                                                </span>
                                            </div>
                                        @endforeach
                                        <div class=" mb-1">
                                            <div class="text-center">
                                                <button type="" class="btn btn-sm btn-outline-primary"
                                                    onclick="document.getElementById('update-ingredients').submit()">
                                                    {{ __('Update') }}
                                                </button>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </form>
                                <form id="delete-ingredient-{{ $ingredient->id }}" method="post"
                                    action="{{ route('items.delete-ingredient', $ingredient) }}">@csrf
                                    @method('delete')
                                </form>
                                <form method="POST" action="{{ route('items.add-ingredients', $item) }}">
                                    @csrf
                                    <div class="text-right after-add-more"style="margin-bottom:10px;">
                                        <a class="add-more" type="button"style="text-decoration:none">Add
                                            Another Ingredient</a>
                                    </div>
                                    <!-- Copy Fields -->
                                    <div class="copy hide">
                                        <div class="input-group control-group" style="margin-bottom:10px">
                                            <select id="ingredient_name" class="form-control form-select" name="ids[]"
                                                required style="float: left; width: inaitial; ">
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
                                                name="quantities[]" value="{{ old('ingredient_quantity') }}" required
                                                autocomplete="ingredient_quantity" autofocus style="float: left;">
                                            @error('ingredient_quantity')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <select id="ingredient_unit" class="form-control form-select" name="units[]"
                                                required
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
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br>
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
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#addCollapse" aria-expanded="false"
                        aria-controls="addCollapse">
                        <i class="feather icon-plus"></i> Add Product
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="addCollapse" style="width: 100%;border-width:0px" class="accordion-collapse collapse "
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('products.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3 mb-1">
                                    <label for="name"
                                        class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <select id="name" class="form-control form-select" name="item_id" required
                                        style="float: left; width: inaitial; ">
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                    <label for="quantity"
                                        class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
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
                                <div class="col-sm-3 mb-1">
                                    <label for="price"
                                        class=" col-form-label text-sm-start">{{ __('Price') }}</label>
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
                    <th>Name</th>
                    <th>Container</th>
                    <th>Quantity</th>
                    <th class="text-right">Price (Tsh)</th>
                    <th>Last Updated</th>
                    <th style="max-width: 50px">Status</th>
                    <th></th>
                    <th></th>
                    <th></th>

                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $product->item->name }}</td>
                            <td>{{ $product->container }}</td>
                            <td>{{ $product->quantity . ' ' . $product->unit }}</td>
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
                            <td>
                                <a href="{{ route('products.show', $product) }}"
                                    class="btn btn-sm btn-outline-info collapsed" type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                            </td>
                            <td class="text-center">
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
                                                            name="item_id" required autocomplete="container" autofocus>
                                                            @foreach ($items as $i)
                                                                <option
                                                                    value="{{ $i->id }}"{{ $product->item_id == $i->id ? 'selected' : '' }}>
                                                                    {{ $i->name }}</option>
                                                            @endforeach
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </select>
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="container"
                                                            class=" col-form-label text-sm-start">{{ __('Container') }}</label>
                                                        <input id="container" type="text" placeholder="Glass"
                                                            class="form-control @error('container') is-invalid @enderror"
                                                            name="container"
                                                            value="{{ old('container', $product->container) }}" required
                                                            autocomplete="container" autofocus>
                                                        @error('container')
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
                                                                value="{{ old('quantity', $product->quantity) }}"
                                                                required autocomplete="quantity" autofocus
                                                                style="float: left;">
                                                            <select class="form-control form-select" name="unit"
                                                                required
                                                                style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                <option value="Kilograms"
                                                                    {{ $product->unit == 'Kilograms' ? 'selected' : '' }}>
                                                                    Kilograms</option>
                                                                <option value="Litres"
                                                                    {{ $product->unit == 'Litres' ? 'selected' : '' }}>
                                                                    Litres</option>
                                                                <option value="Counts"
                                                                    {{ $product->unit == 'Counts' ? 'selected' : '' }}>
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
                                                        <label for="price"
                                                            class="col-form-label text-sm-start">{{ __('Price') }}</label>
                                                        <input id="price" type="number" placeholder="Tsh"
                                                            class="form-control @error('price') is-invalid @enderror"
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
                            <td class="text-center">
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
    <br>
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h5 class="my-0">
                        <b>{{ __('RECENT SALES') }}
                        </b>
                    </h5>
                </div>
                <div class="col-sm-7">
                    <form action="{{ route('items.show',$item) }}" method="GET" id="filter-form">
                        @csrf
                        <div class="row"><div class="col-6"></div>
                            <div class="col-md-6 text-center">
                                <div class="input-group">
                                    <label for="filteredDate" class=" col-form-label">Filter By Date: </label>
                                    <input id="filteredDate" type="date" style="border-radius:0.375rem;"
                                        class="form-control mx-1 @error('filteredDate') is-invalid @enderror"
                                        name="filteredDate" value="{{ $filteredDate }}" required
                                        onchange="this.form.submit()">
                                    @error('filteredDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="data-tebo2"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th class="text-right">Price (Tsh)</th>
                    <th>Date</th>
                    <th>Seller</th>
                </thead>
                <tbody>
                    @foreach ($filteredSales as $index => $filteredSale)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $filteredSale->name }}</td>
                            <td>{{ $filteredSale->container . ' of ' . $filteredSale->quantity . ' ' . $filteredSale->unit }}</td>
                            <td class="text-right">{{ number_format($filteredSale->price, 0, '.', ',') }} </td>
                            <td class="">{{ $filteredSale->created_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td>{{ $filteredSale->seller }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function showForm1() {
            x = document.getElementById('edit-form')
            y = document.getElementById('edit-btn')
            if (x.style.display == 'block') {
                x.style.display = 'none'
                y.innerHTML = 'Add Ingredients'
                y.style.color = 'blue'
            } else {
                x.style.display = 'block'

                y.innerHTML = 'Close'
                y.style.color = 'red'
            }
        }

        function showForm2() {
            x = document.getElementById('edit-form')
            y = document.getElementById('edit-btn')
            if (x.style.display == 'block') {
                x.style.display = 'none'

                y.innerHTML = 'Change Ingredients'
                y.style.color = 'blue'
            } else {
                x.style.display = 'block'
                y.innerHTML = 'Close'
                y.style.color = 'red'
            }
        }
    </script>
@endsection
@section('script')
<script>
    $("#filter-form").on("submit", function(e) {
        e.preventDefault();
        var url = $("#filter-form").attr("action");
        var newUrl = `${url}?filteredDate=${$(e.target).val()}`;
        window.location.assign(newUrl);
    });
</script>
@endsection
