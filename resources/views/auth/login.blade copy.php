@extends('layouts.app')
@section('title')
    Login - Water Com
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
            <div class=" shadow-lg card"style="min-width: 250px; ">
                <div class="card-header text-center"
                    style="background:rgb(185, 185, 247); color: var(--first-color); font-weight:bold">
                    {{ __('Tanga Water Com Login') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="mobile" class="col-form-label text-sm-start">{{ __('Mobile Number') }}</label>

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

                        <div class="row mb-3">
                            <label for="password"
                                class="csol-sm-4 col-form-label text-sm-start">{{ __('Password') }}</label>

                            <div class="">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{--
                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}

                        <div class="row mb-0">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    {{ __('Login') }}
                                </button>

                            </div>
                            @if (Route::has('password.request'))
                                <div class="text-center pt-2">

                                    <a class="btn-link" style="text-decoration: none; font-size:12px"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-9 text-end p-4 align-self-end">
            <img class="" height="300px" src="{{ asset('images/water.png') }}" alt="">
        </div>

    </div>
@endsection
<div class="text-center text-bold pt-4" style="color:white">
    <div class="row">
        <div class="col-1">
            <a class="btn btsn-sm btn-outline-light"
                href="{{ route('home') }}"style="color:white; text-decoration:none"><i class="fa fa-arrow-left"></i>
            </a>
        </div>
        <div class="col-10">
            <h1 style="text-shadow: 3px 3px rgb(50, 49, 49);font-family:Verdana, Geneva, Tahoma, sans-serif;"><b>
                    TANGA WATER COM
                </b></h1>
        </div>
    </div>
</div>
