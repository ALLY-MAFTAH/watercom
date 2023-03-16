@extends('layouts.app')
@section('title')
    Settings
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
                            <b>SETTINGS
                            </b>
                        </h5>
                    </div>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#addCollapse" aria-expanded="false" aria-controls="addCollapse">
                        <i class="feather icon-plus"></i> Add New Setting
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="addCollapse" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('settings.add') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 mb-1">
                                    <label for="key" class=" col-form-label text-sm-start">{{ __('Key') }}</label>
                                    <div class="">
                                        <input id="key" type="text" placeholder="Key"
                                            class="form-control @error('key') is-invalid @enderror" name="key"
                                            value="{{ old('key') }}" required autocomplete="key" autofocus>
                                        @error('key')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <label for="value" class=" col-form-label text-sm-start">{{ __('Value') }}</label>
                                    <div class="">
                                        <input id="value" type="text" placeholder="Value"
                                            class="form-control @error('value') is-invalid @enderror" name="value"
                                            value="{{ old('value') }}" required autocomplete="value" autofocus>
                                        @error('value')
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
            @foreach ($settings as $key => $value)
            @if ($value!="")
                <br>
                <div class="text-start mb-1">
                    <div class="row">
                        <div class="col-11">

                            <form method="POST" action="{{ route('settings.edit') }}" id="add-setting-{{ $key }}">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-2">
                                        <label for="name"
                                            class=" col-form-label text-sm-start">{{ $key }}</label>
                                        <input type="text" hidden name="key" value="{{ $key }}">
                                    </div>
                                    <div class="col-10">
                                        <div class="input-group">
                                            <input id="value" type="text" placeholder=""
                                                class="form-control @error('value') is-invalid @enderror" name="value"
                                                value="{{ old('value', $value) }}" required autocomplete="value">
                                            <button href="" type="submit" class="btn  btn-outline-primary"
                                                onclick="document.getElementById('add-setting-{{ $key }}').submit()">
                                                Save</button>
                                        </div>
                                        @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-1">
                            <a href="#" class="btn text-danger"
                                onclick="if(confirm('Are you sure want to delete {{ $key }}?')) document.getElementById('delete-setting-{{ $key }}').submit()">
                                <i class="fa fa-trash"></i>
                            </a>
                            <form id="delete-setting-{{ $key }}" method="post"
                                action="{{ route('settings.delete', $key) }}">@csrf @method('delete')
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
@section('scripts')
@endsection
