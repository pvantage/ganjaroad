<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('front/general.resend_activation_code')</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/login.css') }}">
    <!--end of page level css-->
	@include('layouts/global_head')
</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <div class="box1">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="img-responsive mar">
            <h3 class="text-primary">@lang('front/general.resend_activation_code')</h3>
                <!-- Notifications -->
                @include('notifications')

				{!! Form::open(array('url' => URL::to('activation'), 'method' => 'post', 'id' => 'nearme', 'class' => 'omb_loginForm', 'files'=> false)) !!}
                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                        <label class="sr-only">@lang('front/general.email')</label>
                        <input type="email" class="form-control" name="email" placeholder="@lang('front/general.email')" value="{!! old('email') !!}">
                    </div>
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>

                    <input type="submit" class="btn btn-block btn-primary" value="@lang('front/general.send_code')">
				{!! Form::close() !!}
            </div>
        <div class="bg-light animation flipInX">
            <a href="{{ route('home') }}" id="forgot_pwd_title">@lang('front/general.go_to_home')</a>
        </div>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ asset('assets/js/frontend/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/frontend/bootstrap.min.js') }}"></script>
<!--global js end-->

</body>
</html>
