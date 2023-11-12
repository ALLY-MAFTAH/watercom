@extends('layouts.app')
@section('title')
    Expenses
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <form action="{{ route('expenses.index') }}" method="GET" id="filter-form">
                <div class="row">
                    @csrf
                    <div class="col">
                        <div class="text-left">
                            <h5 class="my-0">
                                <span class="">
                                    <b>{{ __('EXPENDITURES') . ' - ' }}
                                    </b>
                                    <div class="btn btn-icon round"
                                        style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                        {{ $expenses->count() }}
                                    </div>
                                </span>
                            </h5>
                        </div>
                    </div>
                    <div class="col text-right">
                        <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo">

                            <i class="feather icon-plus"></i> Add Expense

                        </a>
                    </div>
                    <div class="col text-center">
                        <div class="input-group">
                            <label for="filteredDate" class=" col-form-label">Filter By Date: </label>
                            <input id="filteredDate" type="date" style="border-radius:0.375rem;"
                                class="form-control mx-1 @error('filteredDate') is-invalid @enderror" name="filteredDate"
                                value="{{ $filteredDate }}" required onchange="this.form.submit()">
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
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('expenses.add') }}">
                            @csrf
                            <div class="row">

                                <div class="col-sm-3 mb-1">
                                    <label for="title" class=" col-form-label text-sm-start">{{ __('Title') }}</label>
                                    <div class="">
                                        <input id="title" type="text" placeholder="Title"
                                            class="form-control @error('title') is-invalid @enderror" name="title"
                                            value="{{ old('title') }}" required autocomplete="title" autofocus>
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-1">
                                    <label for="description"
                                        class=" col-form-label text-sm-start">{{ __('Description') }}</label>
                                    <div class="">
                                        <input id="description" type="text" placeholder="Description"
                                            class="form-control @error('description') is-invalid @enderror"
                                            name="description" value="{{ old('description') }}" autocomplete="description"
                                            autofocus>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3 mb-1">
                                    <label for="date"
                                        class=" col-form-label text-sm-start">{{ __('Expense Date') }}</label>
                                    <div class="">
                                        <input id="date" type="date"
                                            class="form-control @error('date') is-invalid @enderror" name="date"
                                            value="{{ old('date') }}" required autocomplete="date" autofocus>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-1">
                                    <label for="amount" class=" col-form-label text-sm-start">{{ __('Amount') }}</label>
                                    <div class="">
                                        <input id="amount" type="number" placeholder="00"
                                            class="form-control @error('amount') is-invalid @enderror" name="amount"
                                            value="{{ old('amount') }}" required autocomplete="amount" autofocus>
                                        @error('amount')
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
            <table id="data-tebo1"
                class="dt-responsive  table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                    <th class="text-center">Expense Date</th>
                    <th>Issued by</th>
                    {{-- <th>Created At</th> --}}
                    {{-- <th style="max-width: 50px">Status</th> --}}
                    <th></th>
                    <th></th>

                </thead>
                <tbody>
                    @php
                    $totalAmount = 0;
                @endphp
                    @foreach ($expenses as $index => $expense)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td style="max-width: 150px">{{ $expense->title }}</td>
                            <td style="max-width: 250px">{{ $expense->description }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 0, '.', ',') . ' TZS' }}</td>
                            @php
                                $totalAmount += $expense->amount;
                            @endphp
                            <td class="text-center">
                                {{ Illuminate\Support\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                            <td> {{ $expense->user->name }} </td>
                            {{-- <td class="">{{ $expense->updated_at->format('D, d M Y \a\t H:i:s') }} </td> --}}
                            {{-- <td class="text-center">
                                <form id="toggle-status-form-{{ $expense->id }}" method="POST"
                                    action="{{ route('expenses.toggle-status', $expense) }}">
                                    <div class="form-check form-switch ">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status-switch-{{ $expense->id }}"
                                            class="form-check-input " @if ($expense->status) checked @endif
                                            @if ($expense->trashed()) disabled @endif value="1"
                                            onclick="this.form.submit()" />
                                    </div>
                                    @csrf
                                    @method('PUT')
                                </form>
                            </td> --}}
                            {{-- <td>
                                <a href="{{ route('expenses.show', $expense) }}"
                                    class="btn btn-sm btn-outline-info collapsed" type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                            </td> --}}
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $expense->id }}"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="feather icon-edit"></i> Edit
                                </a>
                                <div class="modal modal-sm fade" id="editModal-{{ $expense->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Expense</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('expenses.edit', $expense) }}">
                                                    @method('PUT')
                                                    @csrf

                                                    <div class="text-start mb-1">
                                                        <label for="title"
                                                            class=" col-form-label text-sm-start">{{ __('Title') }}</label>
                                                        <input id="title" type="text" placeholder="Title"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            name="title" value="{{ old('title', $expense->title) }}"
                                                            required autocomplete="title" autofocus>
                                                        @error('title')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="description"
                                                            class=" col-form-label text-sm-start">{{ __('Description') }}</label>
                                                        <input id="description" type="text" placeholder="Description"
                                                            class="form-control @error('description') is-invalid @enderror"
                                                            name="description"
                                                            value="{{ old('description', $expense->description) }}"
                                                            autocomplete="description" autofocus>
                                                        @error('description')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="date"
                                                            class="col-form-label text-sm-start">{{ __('Date') }}</label>
                                                        <input id="date" type="date"
                                                            class="form-control @error('date') is-invalid @enderror"
                                                            name="date" value="{{ old('date', $expense->date) }}"
                                                            required autocomplete="date" autofocus>
                                                        @error('date')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="amount"
                                                            class="col-form-label text-sm-start">{{ __('Amount') }}</label>
                                                        <input id="amount" type="number" placeholder="00"
                                                            class="form-control @error('amount') is-invalid @enderror"
                                                            name="amount" value="{{ old('amount', $expense->amount) }}"
                                                            required autocomplete="amount" autofocus>
                                                        @error('amount')
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
                                    onclick="if(confirm('Are you sure want to delete {{ $expense->name }}?')) document.getElementById('delete-expense-{{ $expense->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-expense-{{ $expense->id }}" method="post"
                                    action="{{ route('expenses.delete', $expense) }}">@csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="font-size: 18px;font-weight:bold">Total</td>
                        <td style="font-size: 18px;font-weight:bold" class="text-end">
                            {{ number_format($totalAmount, 0, '.', ',') }} TZS</td>
                            <td></td>
                            <td></td>
                        </tr>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
