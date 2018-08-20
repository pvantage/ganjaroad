<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('front/general.register_new_account')</title>
    <!--global css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-social.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!--end of global css-->
    <!--page level css starts-->
    <link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/register.css') }}">
    <!--end of page level css-->
	@include('layouts/global_head')
</head>
<body>
<div class="container">
    <!--Content Section Start -->
    <div class="row">
        <div class="box animation flipInX">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="img-responsive mar">
            <h3 class="text-primary">@lang('front/general.register')</h3>
            <!-- Notifications -->
            @include('notifications')
            @if($errors->has())
                @foreach ($errors->all() as $error)
                    <div class="text-danger">{{ $error }}</div>
                @endforeach
            @endif
            <form action="{{ route('register') }}" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                    <label class="sr-only"> @lang('front/general.first_name')</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="@lang('front/general.first_name')"
                           value="{!! old('first_name') !!}" required>
                    {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                    <label class="sr-only"> @lang('front/general.last_name')</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="@lang('front/general.last_name')"
                           value="{!! old('last_name') !!}" required>
                    {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('email', 'has-error') }}">
                    <label class="sr-only"> @lang('front/general.email')</label>
                    <input type="email" class="form-control" id="Email" name="email" placeholder="@lang('front/general.email')"
                           value="{!! old('email') !!}" required>
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>
				
				
				<div class="form-group {{ $errors->first('country', 'has-error') }}">
                    <label class="sr-only"> @lang('users/form.select-country')</label>
					{!! Form::SelectCountry('country', '',(old('country')) ? old('country') : TemplateHelper::getUserCurrentCountryCode(), array('class' => 'form-control select2',  'id' => 'country', 'placeholder'=>trans('users/form.select-country'), 'required' => 'required')) !!}
                    {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                </div>
				
				<div class="state-wrapper form-group {{ $errors->first('state', 'has-error') }}">
                    <label class="sr-only"> @lang('users/form.select-state')</label>
					{!! Form::SelectState('state', '',(old('state')) ? old('state') : TemplateHelper::getUserCurrentState(), array('class' => 'form-control select2',  'id' => 'state', 'placeholder'=>trans('users/form.select-state'), 'short' => true)) !!}
                    {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
                </div>
				
				 <div class="form-group {{ $errors->first('city', 'has-error') }}">
                    <label class="sr-only"> @lang('users/form.city')</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="@lang('users/form.city')" value="{!! (old('city')) ? old('city') : TemplateHelper::getUserCurrentCity() !!}" required>
                    {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                </div>
				
                <div class="form-group {{ $errors->first('password', 'has-error') }}">
                    <label class="sr-only"> @lang('front/general.password')</label>
                    <input type="password" class="form-control" id="Password1" name="password" placeholder="@lang('front/general.password')">
                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                    <label class="sr-only"> @lang('front/general.password_confirm')</label>
                    <input type="password" class="form-control" id="Password2" name="password_confirm"
                           placeholder="@lang('front/general.password_confirm')">
                    {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="newsletter" value="1"> @lang('front/general.register_newsletter')</a>
                    </label>
                </div>
				<div class="checkbox">
                    <label>
                        <input type="checkbox" name="terms" value="1"> @lang('front/general.register_terms_accept') <a href="{{ URL::to('terms') }}" target="_blank"> @lang('front/general.register_terms')</a>
                    </label>
                </div>
                <input type="submit" class="btn btn-block btn-primary" value="Sign up" name="submit">
				<div class="or-separator">
					@lang('front/general.register_or')
				</div>
				<a href="{{ url('login/facebook') }}" class="btn btn-block btn-social btn-facebook">
					<span class="fa fa-facebook"></span> @lang('front/general.facebook_sign_up')
				</a>
				<a href="{{ url('login/twitter') }}" class="btn btn-block btn-social btn-twitter">
					<span class="fa fa-twitter"></span> @lang('front/general.twitter_sign_up')
				</a>
				<div class="bottom-block">
					@lang('front/general.register_already_have_account') <a href="{{ route('login') }}"> @lang('front/general.sign_in')</a>
				</div>
            </form>
        </div>
    </div>
    <!-- //Content Section End -->
</div>
<!--global js starts-->
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<!--global js end-->
<script type="text/javascript">
    jQuery(document).ready(function() {
		setState();
		jQuery('#country').change(function() {
			setState();
		});
		
        jQuery("input[type='checkbox'],input[type='radio']").iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
    });
	
	function setState() {
		if(jQuery('#country').val() == 'US') {
			jQuery('.state-wrapper').show();
			jQuery('#state').attr('required', 'required');
		} else {
			jQuery('.state-wrapper').hide();
			jQuery('#state').removeAttr('required')
		}
	}
</script>
</body>
</html>
