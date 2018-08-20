@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.contact.page_title')
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/contact.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">@lang('front/general.contact.page_title')</li>
	</ol>               
@stop


{{-- Page content --}}
@section('content')
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.contact.page_title')</h1>
		</div>
		<hr>
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					{!! TemplateHelper::renderBlock('contact', false) !!}
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">	
					<div class="has-error">
						@if($errors->has())
						   @foreach ($errors->all() as $error)
								<span class="help-block">{{ $error }}</span>
						  @endforeach
						@endif
					</div>
			
					{!! Form::open(array('url' => URL::to('contact'), 'method' => 'post', 'id' => 'contact', 'class' => 'contact', 'files'=> false)) !!}
						<div class="form-group">
							{!! Form::text('name', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('front/general.contact.input_name'))) !!}
						</div>
						<div class="form-group">
							{!! Form::text('email', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('front/general.contact.input_email'))) !!}
						</div>
						<div class="form-group">
							{!! Form::textarea('message', null, array('class' => 'textarea form-control', 'required' => 'required', 'rows'=>'5', 'placeholder'=>trans('front/general.contact.input_msg'), 'style'=>'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"')) !!}
						</div>
						<div class="form-group">
							{!! Recaptcha::render() !!}
						</div>
						
						<div class="input-group">
							<button class="btn btn-primary" type="submit">@lang('front/general.submit')</button>
							<button class="btn btn-danger" type="reset">@lang('front/general.cancel')</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
    
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
