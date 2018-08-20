@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.get_access')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.get_access')</li>
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
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.get_access')</h1>
		</div>
		<hr>
		<div class="content get-access">
			<div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
			{!! Form::open(array('url' => URL::to('access'), 'method' => 'post', 'id' => 'access', 'class' => 'bf', 'files'=> false)) !!}
			
				<div class="row">
					<div class="col-sm-12">
				
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('type', trans('access/form.type')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::select('type', $types, $type, array('class' => 'form-control select2', 'required' => 'required')) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('business', trans('access/form.business')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('business', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('access/form.business'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('contact', trans('access/form.contact')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('contact', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('access/form.contact'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('email', trans('access/form.email')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('email', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('access/form.email'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('phone', trans('access/form.phone')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('phone', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('access/form.phone'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('address', trans('access/form.address')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::textarea('address', null, array('class' => 'textarea form-control', 'rows'=>'5', 'placeholder'=>trans('access/form.address'), 'required' => 'required', 'style'=>'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"')) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group text-center">
							<button type="submit" class="btn btn-success">@lang('front/general.send')</button>
						</div>
							
					</div>
				</div>
			
			{!! Form::close() !!}
			
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
