@extends('layouts.app')
@section('title')
    Users
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class="text-left">
                        <h5 class="my-0">
                            <span class="">
                                <b>{{ __('USERS') .' - '}}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $users->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">

                        <i class="feather icon-plus"></i> Add User

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('users.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4 mb-1">
                                    <label for="add_role_id" class=" col-form-label text-sm-start">{{ __('Role') }}</label>
                                    <select id="add_role_id" type="text"
                                        class="form-control form-select @error('role_id') is-invalid @enderror"
                                        name="role_id" value="{{ old('role_id') }}" required autocomplete="role_id"
                                        autofocus>
                                        <option value="">--</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="">
                                        <input id="name" type="text" placeholder="Name"
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
                                    <label for="mobile" class=" col-form-label text-sm-start">{{ __('Mobile Number') }}</label>
                                    <div class="">
                                        <input id="mobile" type="tel" placeholder="Eg; 0712345678" pattern="0[0-9]{9}" maxlength="10"
                                            class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                            value="{{ old('mobile') }}" required autocomplete="phone" autofocus>
                                        @error('mobile')
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
                    <th>Mobile Number</th>
                    <th>Role</th>
                    <th>Last Updated</th>
                    <th style="max-width: 50px">Status</th>
                    <th></th>
                    <th></th>
                    <th></th>

                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td> {{ $user->role->name }} </td>
                            <td class="">{{ $user->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                            <td class="text-center">
                                <form id="toggle-status-form-{{ $user->id }}" method="POST"
                                    action="{{ route('users.toggle-status', $user) }}">
                                    <div class="form-check form-switch ">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status-switch-{{ $user->id }}"
                                            class="form-check-input " @if ($user->status) checked @endif
                                            @if ($user->trashed()) disabled @endif value="1"
                                            onclick="this.form.submit()" />
                                    </div>
                                    @csrf
                                    @method('PUT')
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info collapsed"
                                    type="button">
                                    <i class="feather icon-edit"></i> View
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $user->id }}"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="feather icon-edit"></i> Edit
                                </a>
                                <div class="modal modal-sm fade" id="editModal-{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('users.edit', $user) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="text-start mb-1">
                                                        <label for="edit_role_id"
                                                            class=" col-form-label text-sm-start">{{ __('Role') }}</label>
                                                        <select id="edit_role_id" type="text"
                                                            class="form-control form-select @error('role_id') is-invalid @enderror"
                                                            name="role_id" value="{{ old('role_id', $user->role_id) }}"
                                                            required autocomplete="role_id" autofocus>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $role->id == $user->role_id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('role_id')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="name"
                                                            class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                                        <input id="name" type="text" placeholder="Name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name', $user->name) }}"
                                                            required autocomplete="name" autofocus>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-start mb-1">
                                                        <label for="mobile"
                                                            class="col-form-label text-sm-start">{{ __('Mobile Number') }}</label>
                                                        <input id="mobile" type="tel" placeholder="Eg; 0712345678" pattern="0[0-9]{9}" maxlength="10"
                                                            class="form-control @error('mobile') is-invalid @enderror"
                                                            name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                                            required autocomplete="phone" autofocus>
                                                        @error('mobile')
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
                                    onclick="if(confirm('Are you sure want to delete {{ $user->name }}?')) document.getElementById('delete-user-{{ $user->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-user-{{ $user->id }}" method="post"
                                    action="{{ route('users.delete', $user) }}">@csrf @method('delete')
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
