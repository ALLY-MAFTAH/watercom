<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Login - {{ setting('App Name', 'Tanga Watercom Depot') }}
    </title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        #body-pd {
            background: rgb(72, 11, 176);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body id="body-pd">
    <div class="text-center text-bold pt-4" style="color:white">
        <div class="row">
            <div class="col-1">
                <a class="btn btsn-sm btn-outline-light" href="https://morningskygeneral.co.tz"
                    style="color:white; text-decoration:none"><i class="fa fa-arrow-left"></i>
                </a>
            </div>
            <div class="col-10 text-center pb-4">
                <h1 class="text-center"
                    style="text-shadow: 3px 3px rgb(50, 49, 49);font-family:Verdana, Geneva, Tahoma, sans-serif;"><b>
                        {{-- TANGA WATER COM --}}
                        {{ setting('App Name', 'Tanga Watercom Depot') }}

                    </b></h1>
            </div>
        </div>
    </div>
    <div class="row justify-content-md-center text-center">
        <div class="wrapper pb-2">
            <div class="logo">
                <img src="{{ asset('images/water.png') }}" alt="">
            </div>
            <div class="text-center mt-3 mb-0 name">
                Login
            </div>
            <form class="p-3 mt-0" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-field d-flex align-items-center">
                    <span class="fa fa-user"></span>
                    <input class=" @error('mobile') is-invalid @enderror" type="tel" name="mobile" id="mobile"
                        value="{{ old('mobile') }}"placeholder="Username" pattern="0[0-9]{9}" maxlength="10"
                        autocomplete="phone" required>
                    @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-field d-flex align-items-center">
                    <span class="fa fa-key"></span>
                    <input type="password" name="password" id="pwd" placeholder="Password" autocomplete="password"
                        required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn mt-3">Login</button>
            </form>
            <div class="text-center fs-6">
                <a href="{{ route('password.request') }}">Forgot password?</a>
                {{-- <a href="#">Forget password?</a> or <a href="#">Sign up</a> --}}
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
                $('a').attr('disabled', 'disabled');
            });
        });
    </script>
    <script src="https://use.fontawesome.com/3076bdb328.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.7.2/css/all.css"></script>


</body>

</html>

{{--


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
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection
@section('content')
    <div class="row justify-content-md-center">
        <div class="wrapper">
            <div class="logo">
                <img src="https://www.freepnglogos.com/uploads/twitter-logo-png/twitter-bird-symbols-png-logo-0.png"
                    alt="">
            </div>
            <div class="text-center mt-4 name">
                Twitter
            </div>
            <form class="p-3 mt-3">
                <div class="form-field d-flex align-items-center">
                    <span class="far fa-user"></span>
                    <input type="text" name="userName" id="userName" placeholder="Username">
                </div>
                <div class="form-field d-flex align-items-center">
                    <span class="fas fa-key"></span>
                    <input type="password" name="password" id="pwd" placeholder="Password">
                </div>
                <button class="btn mt-3">Login</button>
            </form>
            <div class="text-center fs-6">
                <a href="#">Forget password?</a> or <a href="#">Sign up</a>
            </div>
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
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.7.2/css/all.css"></script>
@endsection --}}
