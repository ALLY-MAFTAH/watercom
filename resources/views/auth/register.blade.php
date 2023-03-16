@extends('layouts.app')
@section('title')
    Register
@endsection
@section('style')
    <style>
        #body-pd {
            background: rgb(72, 11, 176);
            /* For browsers that do not support gradients */
            background-image: linear-gradient(to bottom right, rgb(72, 11, 176), rgb(72, 11, 176), rgb(107, 36, 231), white, white);
        }
    </style>
@endsection
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-sm-3 pr-5">
            <div class="shadow card" style="min-width: 250px">
                <div class="card-header text-center" style="background:rgb(185, 185, 247); color: var(--first-color); font-weight:bold">
                    {{ __('Register') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>

                            <div class="">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class=" col-form-label text-sm-start">{{ __('Email Address') }}</label>

                            <div class="">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class=" col-form-label text-sm-start">{{ __('Password') }}</label>

                            <div class="">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm"
                                class=" col-form-label text-sm-start">{{ __('Confirm Password') }}</label>

                            <div class="">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                        <div class="text-center">
                            <a class="btn-link" style="text-decoration: none; font-size:12px" href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-9 text-end p-4 align-self-end">
            <img class="" height="300px" src="{{ asset('images/icecream.png') }}" alt="">
        </div>
    </div>
@endsection
<div class="text-center text-bold pt-4" style="color:white">
    <h1 style="text-shadow: 3px 3px rgb(50, 49, 49);font-family:Verdana, Geneva, Tahoma, sans-serif;"><b>
        {{setting('App Name')}} </b></h1>
</div>
