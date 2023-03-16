@extends('layouts.app')
@section('title')
    Roles
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
                                <b>{{ __('ROLES') .' - '}}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $roles->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

                        <i class="feather icon-plus"></i> Add Role

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('roles.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 mb-1">
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
                                <div class="col-sm-6 mb-1">
                                    <label for="description"
                                        class=" col-form-label text-sm-start">{{ __('Description') }}</label>
                                    <div class="">
                                        <input id="description" type="text" placeholder="Description"
                                            class="form-control @error('description') is-invalid @enderror" name="description"
                                            value="{{ old('description') }}" required autocomplete="description" autofocus>
                                        @error('description')
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>No. of Users</th>
                    <th>Last Updated</th>
                    <th style="max-width: 50px">Status</th>
                    <th></th>
                    <th></th>
                    <th></th>

                </thead>
                <tbody>
                    @foreach ($roles as $index => $role)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ $role->users->count() }}</td>
                            <td class="">{{ $role->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td class="text-center">
                                <form id="toggle-status-form-{{ $role->id }}" method="POST"
                                    action="{{ route('roles.toggle-status', $role) }}">
                                    <div class="form-check form-switch ">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status-switch-{{ $role->id }}"
                                            class="form-check-input " @if ($role->status) checked @endif
                                            @if ($role->trashed()) disabled @endif value="1"
                                            onclick="this.form.submit()" />
                                    </div>
                                    @csrf
                                    @method('PUT')
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-outline-info collapsed"
                                    type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $role->id }}"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="feather icon-edit"></i> Edit
                                </a>
                                <div class="modal modal-sm fade" id="editModal-{{ $role->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('roles.edit', $role) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="text-start mb-1">
                                                        <label for="name"
                                                            class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                                        <input id="name" type="text" placeholder=""
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name', $role->name) }}"
                                                            required autocomplete="name" autofocus>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="description"
                                                            class="col-form-label text-sm-start">{{ __('Description') }}</label>
                                                        <input id="description" type="text" placeholder="Description"
                                                            class="form-control @error('description', $role->description) is-invalid @enderror"
                                                            name="description" value="{{ old('description', $role->description) }}"
                                                            required autocomplete="description" autofocus>
                                                        @error('description')
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
                                    onclick="if(confirm('Are you sure want to delete {{ $role->name }}?')) document.getElementById('delete-role-{{ $role->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-role-{{ $role->id }}" method="post"
                                    action="{{ route('roles.delete', $role) }}">@csrf @method('delete')
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
