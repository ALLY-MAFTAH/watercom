@extends('layouts.app')
@section('title')
    Customers
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
                                <b>{{ __('CUSTOMERS') .' - '}}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $customers->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="feather icon-plus"></i> Add Customer
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('customers.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Full Name') }}</label>
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
                                <div class="col-sm-6 mb-1">
                                    <label for="phone"
                                        class=" col-form-label text-sm-start">{{ __('Mobile Number') }}</label>
                                    <div class="">
                                        <input id="phone" type="tel" placeholder="Eg; 0712345678" pattern="0[0-9]{9}" maxlength="10"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone') }}" required autocomplete="phone" autofocus>
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
                    <th>Full Name</th>
                    <th>Mobile Number</th>
                    <th>Number of Purchases</th>
                    <th class="text-center">Actions</th>

                </thead>
                <tbody>
                    @foreach ($customers as $index => $customer)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->goods->count() }}</td>
                            <td class="text-center">
                                <form id="toggle-status-form-{{ $customer->id }}" method="POST" class="px-2"
                                    action="{{ route('customers.toggle-status', $customer) }}">
                                    <div class="form-check form-switch ">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status-switch-{{ $customer->id }}"
                                            class="form-check-input " @if ($customer->status) checked @endif
                                            @if ($customer->trashed()) disabled @endif value="1"
                                            onclick="this.form.submit()" />
                                    </div>
                                    @csrf
                                    @method('PUT')
                                </form>

                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info collapsed mx-1"
                                    type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-primary collapsed mx-1" type="button"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $customer->id }}"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="feather icon-edit"></i> Edit
                                </a>
                                <div class="modal modal-sm fade" id="editModal-{{ $customer->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('customers.edit', $customer) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="text-start mb-1">
                                                        <label for="name"
                                                            class=" col-form-label text-sm-start">{{ __('Full Name') }}</label>
                                                        <input id="name" type="text" placeholder=""
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name', $customer->name) }}"
                                                            required autocomplete="name" autofocus>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="phone"
                                                            class="col-form-label text-sm-start">{{ __('Mobile Number') }}</label>
                                                        <input id="phone" type="text" placeholder="Eg; 0712345678" pattern="0[0-9]{9}" maxlength="10"
                                                            class="form-control @error('phone', $customer->phone) is-invalid @enderror"
                                                            name="phone" value="{{ old('phone', $customer->phone) }}"
                                                            required autocomplete="phone" autofocus>
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

                                <a href="#" class="btn btn-sm btn-outline-danger mx-1"
                                    onclick="if(confirm('Are you sure want to delete {{ $customer->name }}?')) document.getElementById('delete-role-{{ $customer->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-role-{{ $customer->id }}" method="post"
                                    action="{{ route('customers.delete', $customer) }}">@csrf @method('delete')
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
