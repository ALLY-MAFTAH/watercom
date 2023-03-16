@extends('layouts.app')
@section('title')
    Reset Password - Water Com
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
            <div class=" shadow-lg card"style="min-width: 250px">
                <div class="card-header text-center"
                    style="background:rgb(185, 185, 247); color: var(--first-color); font-weight:bold">
                    {{ __('Reset Password') }}
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class=" col-form-label text-md-start">{{ __('Phone Number') }}</label>

                            <div class="">
                                <input id="mobile" type="mobile" placeholder="Eg; 0712345678" pattern="0[0-9]{9}" maxlength="10"
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
                            <label for="password" class=" col-form-label text-md-start">{{ __('Password') }}</label>
                            <div>
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
                                class=" col-form-label text-md-start">{{ __('Confirm Password') }}</label>
                            <div>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                        <div class="text-center pt-2">
                            <a class="btn-link" style="text-decoration: none; font-size:12px"
                                href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
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
    <h1 style="text-shadow: 3px 3px rgb(50, 49, 49);font-family:Verdana, Geneva, Tahoma, sans-serif;">
        <b>
            {{-- {{setting('App Name')}} --}}
            TANGA WATER COM
        </b>
    </h1>
</div>
