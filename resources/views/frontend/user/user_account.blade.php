@extends('layouts.default')

{{-- Page title --}}
@section('title')
    User Account
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/iCheck/css/minimal/blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/user_account.css') }}">

@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">@lang('front/general.user_account')</li>
	</ol>               
@stop

@section('leftcol')
	@section('leftcol_content')
		@include('layouts/user_leftcol')
	@stop
	@parent
@stop

{{-- Page content --}}
@section('content')
    <hr>
    <div class="wrapper">
        <div class="welcome">
            <h3>@lang('front/general.user_account')</h3>
        </div>
        <hr>
		@if($text)
			{!! $text !!}
			<hr>
		@endif
        <div class="row">
			<div class="col-md-12">
				<!--main content-->
				<div class="position-center">
					<div class="has-error">
						@if($errors->has())
						   @foreach ($errors->all() as $error)
								<span class="help-block">{{ $error }}</span>
						  @endforeach
						@endif
					</div>
					{!! Form::open(array('url' => route('my-account'), 'method' => 'post', 'role' => 'form', 'id' => 'tryitForm', 'class' => 'form-horizontal', 'files'=> true)) !!}
					<input type="hidden" name="_method" value="PUT">

					<div class="form-group">
						<label class="col-md-2 control-label">@lang('front/general.avatar'):</label>
						<div class="col-md-10">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
									@if($user->pic)
										@if((!filter_var($user->pic, FILTER_VALIDATE_URL) === false))
											<img src="{{ $user->pic }}" alt="img" class="img-responsive"/>
										@else
											<img src="{{ asset('uploads/users/'.$user->pic) }}" alt="img" class="img-responsive"/>
										@endif
									@else
										<img src="{{ asset('assets/images/200x150.png') }}" alt="..." class="img-responsive"/>
									@endif
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
								<div>
									<span class="btn btn-primary btn-file">
										<span class="fileinput-new">@lang('front/general.select_image')</span>
										<span class="fileinput-exists">@lang('front/general.change')</span>
										<input type="file" name="pic" id="pic" />
									</span>
									<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">@lang('front/general.remove')</span>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group {{ $errors->first('first_name', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('front/general.first_name'):
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-user-md text-primary"></i>
								</span>
								<input type="text" placeholder=" " name="first_name" id="u-name" class="form-control" value="{!! old('first_name',$user->first_name) !!}">
							</div>
							<span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
						</div>
					</div>

					<div class="form-group {{ $errors->first('last_name', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('front/general.last_name'):
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-user-md text-primary"></i>
								</span>
								<input type="text" placeholder=" " name="last_name" id="u-name"
									class="form-control"
									value="{!! old('last_name', $user->last_name) !!}">
							</div>
							<span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
						</div>
					</div>

					<div class="form-group {{ $errors->first('email', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('front/general.email'):
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-envelope text-primary"></i>
								</span>
								<input type="text" placeholder=" " id="email" name="email" class="form-control" value="{!! old('email',$user->email) !!}">
							</div>
							<span class="help-block">{{ $errors->first('email', ':message') }}</span>
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('country', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('users/form.country')
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							{!! Form::SelectCountry('country', '', $user->country, array('class' => 'form-control select2',  'id' => 'country', 'placeholder'=> trans('users/form.country'), 'required' => 'required', 'empty' => true)) !!}
							<span class="help-block">{{ $errors->first('country', ':message') }}</span>
						</div>
					</div>
					
					<div class="state-wrapper form-group {{ $errors->first('state', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('users/form.state')
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							{!! Form::SelectState('state', '', $user->state, array('class' => 'form-control select2',  'id' => 'state', 'placeholder'=> trans('users/form.state'), 'short' => true, 'empty' => true)) !!}
							<span class="help-block">{{ $errors->first('state', ':message') }}</span>
						</div>
					</div>
					
					 <div class="form-group {{ $errors->first('city', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('users/form.city')
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							<input type="text" class="form-control" id="city" name="city" placeholder="@lang('users/form.city')" value="{!! $user->city !!}" required>
							<span class="help-block">{{ $errors->first('city', ':message') }}</span>
						</div>
					</div>

					<div class="form-group {{ $errors->first('password', 'has-error') }}">
						<p class="text-warning col-md-offset-2"><strong>@lang('front/general.change_password_notice')</strong></p>
						<label class="col-lg-2 control-label">
							@lang('front/general.password'):
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							<div class="input-group">
									<span class="input-group-addon">
										<i class="fa fa-fw fa-key text-primary"></i>
									</span>
								<input type="password" name="password" placeholder=" " id="pwd" class="form-control">
							</div>
							<span class="help-block">{{ $errors->first('password', ':message') }}</span>
						</div>
					</div>

					<div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
						<label class="col-lg-2 control-label">
							@lang('front/general.password_confirm'):
							<span class='require'>*</span>
						</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-key text-primary"></i>
								</span>
								<input type="password" name="password_confirm" placeholder=" " id="cpwd" class="form-control">
							</div>
							<span class="help-block">{{ $errors->first('password_confirm', ':message') }}</span>
						</div>
					</div>

					<?php /*
					<div class="form-group">
						<label class="col-lg-2 control-label">Gender: </label>
						<div class="col-lg-6">
							<div class="radio">
								<label>
									<input type="radio" name="gender" value="male" @if($user->gender === "male") checked="checked" @endif />
									Male
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="gender" value="female" @if($user->gender === "female") checked="checked" @endif />
									Female
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="gender" value="other" @if($user->gender === "other") checked="checked" @endif />
									Other
								</label>
							</div>
						</div>
					</div>

					<div>
						<h3 class="text-primary" id="title">Contact: </h3>
					</div>

					<div class="form-group {{ $errors->first('address', 'has-error') }}">
						<label class="col-lg-2 control-label">
							Address:
						</label>
						<div class="col-lg-6">
							<textarea rows="5" cols="30" class="form-control" id="add1" name="address">{!! old('address',$user->address) !!}</textarea>
						</div>
						<span class="help-block">{{ $errors->first('address', ':message') }}</span>
					</div>

					<div class="form-group {{ $errors->first('country', 'has-error') }}">
						<label class="control-label  col-md-2">Select Country: </label>
						<div class="col-md-6">
							{!! Form::select('country', $countries, $user->country,['class' => 'form-control select2', 'id' => 'countries']) !!}
							<span class="help-block">{{ $errors->first('country', ':message') }}</span>
						</div>
					</div>

					<div class="form-group {{ $errors->first('state', 'has-error') }}">
						<label class="col-lg-2 control-label" for="state">State:</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-dot-circle-o text-primary"></i>
								</span>
								<input type="text" placeholder=" " id="state" class="form-control" name="state" value="{!! old('state',$user->state) !!}"/>
							</div>
						</div>
						<span class="help-block">{{ $errors->first('state', ':message') }}</span>
					</div>

					<div class="form-group {{ $errors->first('city', 'has-error') }}">
						<label class="col-lg-2 control-label" for="city">City:</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-dot-circle-o text-primary"></i>
								</span>
								<input type="text" placeholder=" " id="city" class="form-control" name="city" value="{!! old('city',$user->city) !!}"/>
							</div>
						</div>
						<span class="help-block">{{ $errors->first('city', ':message') }}</span>
					</div>

					<div class="form-group {{ $errors->first('postal', 'has-error') }}">
						<label class="col-lg-2 control-label" for="postal">Postal:</label>
						<div class="col-lg-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-fw fa-dot-circle-o text-primary"></i>
								</span>
								<input type="text" placeholder=" " id="postal" class="form-control" name="postal" value="{!! old('postal',$user->postal) !!}"/>
							</div>
							<span class="help-block">{{ $errors->first('postal', ':message') }}</span>
						</div>
					</div>
					*/ ?>
				
					<div class="form-group {{ $errors->first('dob', 'has-error') }}">
						{!! Form::label('dob', trans('front/general.account_dob'), array('class' => 'col-lg-2 control-label')) !!}
						<div class="col-lg-6">
							{!! Form::DateTimeInput('dob', $user->dob, array('class' => 'dob'))!!}
							<span class="help-block">Please use the date picker to select</span>
							<span class="help-block">{{ $errors->first('dob', ':message') }}</span>
						</div>
					</div>

					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button class="btn btn-primary" type="submit">@lang('general.save')</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

<script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

{!! TemplateHelper::includeDateScript() !!}

<script type="text/javascript">
jQuery(document).ready(function() {
	setState();
	jQuery('#country').change(function() {
		setState();
	});
	
	jQuery('input[type="radio"],input[type="checkbox"]').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
		increaseArea: '20%' // optional
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

function format(state) {
    if (!state.id) return state.text; // optgroup
    return "<img class='flag' src='assets/images/countries_flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
}

jQuery("#countries").select2({
    placeholder: "@lang('general.select_country')",
    theme:'bootstrap',
    allowClear: true,
    formatResult: format,
    formatSelection: format,
    templateResult: format,
    escapeMarkup: function (m) {
        return m;
    }
});

</script>
@stop
