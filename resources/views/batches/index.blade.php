@extends('layouts.app')
@section('title')
    Batches
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
                                <b>{{ __('BATCHES') . ' - ' }}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $batches->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                @if (Auth::user()->role_id == 1)
                    <div class="col text-right">
                        <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#batchInCollapse" aria-expanded="false"
                            aria-controls="batchInCollapse">
                            <i class="feather icon-plus"></i> BATCH IN
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div id="batchInCollapse" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                                <form id="add-batch-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col mb-1">
                                            <label for="batch"
                                                class=" col-form-label text-sm-start"><b>{{ __('Batch IN to Stock') }}</b></label>
                                            <div class="input-group control-group after-add-more"style="margin-bottom:10px">
                                                <select id="batch_name" class="input-field form-control form-select"
                                                    name="ids[]" required style="float: left;">
                                                    <option value="">{{ 'Please Select Product' }}</option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}">{{ $stock->name }} -
                                                            {{ $stock->volume }} {{ $stock->measure }} ({{ $stock->unit }})
                                                        </option>
                                                    @endforeach
                                                    @error('batch_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </select>
                                                <input id="batch_quantity" type="number" min="1" placeholder="0"
                                                    class="form-control @error('batch_quantity') is-invalid @enderror"
                                                    name="quantities[]" value="{{ old('batch_quantity') }}" required
                                                    autocomplete="batch_quantity" autofocus
                                                    style="float: left;max-width:130px">
                                                @error('batch_quantity')
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
                                                <select id="batch_name" class="input-field form-control form-select"
                                                    name="ids[]" required style="float: left; width: inaitial; ">
                                                    <option value="">{{ 'Please Select Products' }}</option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}">{{ $stock->name }} -
                                                            {{ $stock->volume }} {{ $stock->measure }}
                                                            ({{ $stock->unit }})
                                                        </option>
                                                    @endforeach
                                                    @error('batch_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </select>
                                                <input id="batch_quantity" type="number" min="1" placeholder="0"
                                                    class="form-control @error('batch_quantity') is-invalid @enderror"
                                                    name="quantities[]" value="{{ old('batch_quantity') }}" required
                                                    autocomplete="batch_quantity" autofocus
                                                    style="float: left;max-width:130px">
                                                @error('batch_quantity')
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
                                            <button id="" class="btn btn-sm btn-outline-primary" type="submit">
                                                {{ __('Submit') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-7">
                            <div id="batchSummary" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div id=" summary-card" class="card">
                                        <div class="card-body text-center">
                                            <h6><b>BATCH SUMMARY</b></h6>
                                            <hr style="margin:0px">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="card my-2">
                                                        <table class="table table-stripped" id="batch-summary">
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <form id="save-batch-form"
                                                    action="{{ route('batches.save') }}"method="post">
                                                    @csrf
                                                    <button id="save-batch-btn" class="btn btn-sm btn-outline-primary"
                                                        type="submit" aria-expanded="false"
                                                        aria-controls="batchSummary">
                                                        {{ __('Save Batch') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                </div>
            </div>
            <table id="data-tebo1"
                class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Created Date</th>
                    <th>IN/OUT</th>
                    <th>Batch Items</th>
                    <th>Created By</th>
                    {{-- @if (Auth::user()->role_id == 1)
                            <th style="max-width: 50px">Batch Status</th>
                            <th hidden></th>
                            <th hidden></th>
                            <th></th>
                        @endif --}}

                </thead>
                <tbody>
                    @foreach ($batches as $index => $batch)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>
                                {{ Illuminate\Support\Carbon::parse($batch->date)->format('D, d M Y \a\t H:i:s') }}
                            </td>
                            <td>{{ $batch->type }}</td>
                            <td>
                                @foreach ($batch->batchItems as $batchItem)
                                    <div>
                                        {{ $batchItem->name }} {{ $batchItem->volume }} {{ $batchItem->measure }} -
                                        {{ $batchItem->quantity }} {{ $batchItem->unit }},
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ $batch->user->name }}</td>

                            {{-- @if (Auth::user()->role_id == 1)
                                    @if ($batch->served_date == null)
                                        <td class="text-center">
                                            <form id="toggle-status-form-{{ $batch->id }}" method="POST"
                                                action="">
                                                <div class="form-check form-switch ">
                                                    <input type="hidden" name="status" value="0">
                                                    <input type="checkbox" name="status"
                                                        id="status-switch-{{ $batch->id }}" class="form-check-input "
                                                        @if ($batch->status) checked @endif
                                                        @if ($batch->trashed()) disabled @endif value="1"
                                                        onclick="this.form.submit()" />
                                                </div>
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        </td>
                                    @else
                                        <td><label style="padding:5px;background:rgb(161, 227, 161)">Served</label></td>
                                    @endif
                                    <td hidden>
                                        <a href="{{ route('batches.show', $batch) }}"
                                            class="btn btn-sm btn-outline-info collapsed" type="button">
                                            <i class="feather icon-edit"></i> View
                                        </a>
                                    </td>

                                    <td hidden class="text-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                                            data-bs-toggle="modal" data-bs-target="#editModal-{{ $batch->id }}"
                                            aria-expanded="false" aria-controls="batchSummary">
                                            <i class="feather icon-edit"></i> Edit
                                        </a>
                                        <div class="modal modal-sm fade" id="editModal-{{ $batch->id }}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Batch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('batches.edit', $batch) }}">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="text-start mb-1">
                                                                <label for="name"
                                                                    class=" col-form-label text-sm-start">{{ __('Full Name') }}</label>
                                                                <input id="name" type="text" placeholder=""
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    name="name"
                                                                    value="{{ old('name', $batch->name) }}" required
                                                                    autocomplete="name" autofocus>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="text-start mb-1">
                                                                <label for="phone"
                                                                    class="col-form-label text-sm-start">{{ __('Mobile Number') }}</label>
                                                                <input id="phone" type="text"
                                                                    placeholder="+25571012345"
                                                                    class="form-control @error('phone', $batch->phone) is-invalid @enderror"
                                                                    name="phone"
                                                                    value="{{ old('phone', $batch->phone) }}" required
                                                                    autocomplete="phone" autofocus>
                                                                @error('phone')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </div>
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
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-outline-danger"
                                            onclick="if(confirm('Are you sure want to delete {{ $batch->name }}?')) document.getElementById('delete-role-{{ $batch->id }}').submit()">
                                            <i class="f"></i>Delete
                                        </a>
                                        <form id="delete-role-{{ $batch->id }}" method="post"
                                            action="{{ route('batches.delete', $batch) }}">@csrf @method('delete')
                                        </form>
                                    </td>
                                @endif --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var selectedStocks;

        $(document).ready(function() {
            $('#add-batch-form').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'add-batch',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        selectedStocks = response;
                        var tbody = $('#batch-summary tbody');
                        tbody.empty();
                        response.forEach(function(selectedStock) {
                            tbody.append(
                                '<tr style="margin:0px;padding:0px"><td class="text-start">' +
                                selectedStock
                                .name + ' ' +
                                selectedStock.volume + ' ' + selectedStock.measure +
                                '</td><td colspan="1"></td><td class="text-start">' +
                                selectedStock.quantity + ' ' + selectedStock.unit +
                                '</td></tr>'
                            );
                        });
                        $("#batchSummary").collapse("show");
                    }
                });
            });

            $('#save-batch-form').submit(function() {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'selectedStocks',
                    value: JSON.stringify(selectedStocks)
                }).appendTo('#save-batch-form');
            });

        });
    </script>
    <script>
        let selectedProducts = [];
        $(document).on('change', '.input-field', function() {
            let selectedProduct = this.value;
            if (selectedProducts.includes(selectedProduct)) {
                alert('Product has already been selected. Please choose another product.');
                this.value = '';
            } else {
                selectedProducts.push(selectedProduct);
            }
        });
    </script>
@endsection
