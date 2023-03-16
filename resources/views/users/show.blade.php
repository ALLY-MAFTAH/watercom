@extends('layouts.app')
@section('title')
    User
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <a href="{{ route('users.index') }}" style="text-decoration: none;font-size:15px">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">

                        <i class="feather icon-plus"></i> Edit User

                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('users.edit', $user) }}">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-sm-4 text-start mb-1">
                                    <label for="role_id" class=" col-form-label text-sm-start">{{ __('Role') }}</label>
                                    <select id="role_id" type="text"
                                        class="form-control form-select @error('role_id') is-invalid @enderror"
                                        name="role_id" value="{{ old('role_id', $user->role_id) }}" required
                                        autocomplete="role_id" autofocus>
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
                                <div class="col-sm-4 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="">
                                        <input id="name" type="text" placeholder=""
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="mobile"
                                        class=" col-form-label text-sm-start">{{ __('Mobile') }}</label>
                                    <div class="">
                                        <input id="mobile" type="mobile" placeholder="Eg; 0712345678" layouts.app="0[0-9]{9}" maxlength="10"
                                            class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                            value="{{ old('mobile', $user->mobile) }}" required autocomplete="phone"
                                            autofocus>
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
                        <div class="col-8">{{ $watercom->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Email:</b> </div>
                        <div class="col-8">{{ $watercom->email }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Role:</b> </div>
                        <div class="col-8">{{ $watercom->role->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b> Created:</b> </div>
                        <div class="col-8">{{ $watercom->created_at->format('D, d M Y \a\t H:i') }}</div>
                    </div>
                </div>
                <div class="col-7">

                </div>
            </div><br>
            <div class="card">
                <div class="card-header">Activities</div>
                <div class="card-body">
                    <table id="data-tebo2"
                    class="dt-responsive nowrap table shadow rounded-3 table-responsive-sm  table-striped table-hover"
                    style="width: 100%">
                    <thead class="shadow rounded-3">
                        <th style="max-width: 20px">#</th>
                        <th>Time</th>
                        <th>Subject</th>
                        <th>Method</th>
                        <th>Ip</th>
                        <th>URL</th>
                        <th width="300px">User Agent</th>

                    </thead>
                    <tbody>
                        @if ($logs->count())
                            @foreach ($logs as $key => $log)
                                @php
                                    $perfomer = '';
                                    $perfomer = App\Models\User::find($log->user_id);
                                @endphp
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $log->updated_at->format('D, d M Y \a\t H:i:s') }} </td>
                                    @if ($perfomer)
                                    <td>{{ $perfomer->name . ' ' . $log->subject }}</td>
                                    @else
                                    <td> <span class="text-white bg-danger">Deleted User</span> {{$log->subject }}</td>
                                    @endif
                                    <td><label class="label label-info">{{ $log->method }}</label></td>
                                    <td class="text-warning">{{ $log->ip }}</td>
                                    <td class="text-success">{{ $log->url }}</td>
                                    <td class="text-danger">{{ $log->agent }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
