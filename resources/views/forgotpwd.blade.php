<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('front/general.forgot_password')</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/forgot.css') }}">
    <!--end of page level css-->
	@include('layouts/global_head')
</head>
<body>
<div class="container">
    <div class="row">
        <div class="box animation flipInX">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="img-responsive mar">
            <h3 class="text-primary">@lang('front/general.forgot_password')</h3>
            <p>@lang('front/general.forgot_password_enter_email')</p>
            @include('notifications')
            <form action="{{ route('forgot-password') }}" class="omb_loginForm" autocomplete="off" method="POST">
                {!! Form::token() !!}
                <div class="form-group">
                    <label class="sr-only"></label>
                    <input type="email" class="form-control email" name="email" placeholder="Email"
                           value="{!! old('email') !!}@if($email = Session::get('email')){!! $email !!}{!! Session::forget('error') !!}@endif">
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
                <div class="form-group">
                    <input class="form-control btn btn-primary btn-block" type="submit" value="Reset Your Password">
                </div>
            </form>
        </div>
    </div>
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!--global js end-->
</body>
</html>
