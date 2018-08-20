<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('front/general.login')</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-social.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
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
            <h3 class="text-primary">@lang('front/general.login')</h3>
                <!-- Notifications -->
                @include('notifications')

                <form action="{{ route('login') }}" class="omb_loginForm"  autocomplete="off" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                        <label class="sr-only">@lang('front/general.email')</label>
                        <input type="email" class="form-control" name="email" placeholder="@lang('front/general.email')" value="{!! old('email') !!}">
                    </div>
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                        <label class="sr-only">@lang('front/general.password')</label>
                        <input type="password" class="form-control" name="password" placeholder="@lang('front/general.password')">
                    </div>
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>

                    <input type="submit" class="btn btn-block btn-primary" value="Login" />
					<div class="or-separator">
						@lang('front/general.register_or')
					</div>
					<a href="{{ url('login/facebook') }}" class="btn btn-block btn-social btn-facebook">
						<span class="fa fa-facebook"></span> @lang('front/general.facebook_login')
					</a>
					<a href="{{ url('login/twitter') }}" class="btn btn-block btn-social btn-twitter">
						<span class="fa fa-twitter"></span> @lang('front/general.twitter_login')
					</a>
					<div class="bottom-block">
						@lang('front/general.register_dont_have_account') <a href="{{ route('register') }}"><strong> @lang('front/general.sign_up')</strong></a>
					</div>
					@if(Request::get('redirect'))
						<input type="hidden" name="redirect" value="{{ Request::get('redirect') }}" />
					@endif
                </form>
            </div>
			
			<div class="bg-light animation flipInX">
				<div class="row">
					<div class="col-md-6">
						<a href="{{ route('forgot-password') }}" id="forgot_pwd_title">@lang('front/general.forgot_password')?</a>
					</div>
					<div class="col-md-6">
						<a href="{{ route('activation') }}">@lang('front/general.resend_activation')</a>
					</div>
				</div>
			</div>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ asset('assets/js/frontend/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/frontend/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<!--global js end-->
<script>
    $(document).ready(function(){
        $("input[type='checkbox']").iCheck({
            checkboxClass: 'icheckbox_minimal-blue'
        });
    });
</script>
</body>
</html>
