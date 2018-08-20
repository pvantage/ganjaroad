@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('report/form.report_issue')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link href="{{ asset('assets/vendors/fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">@lang('report/form.report_issue')</li>
	</ol>                   
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@lang('report/form.report_issue')</h1>
		</div>
		<hr>
		<div class="content report-issue">
			<div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
			{!! Form::open(array('url' => URL::to('report'), 'method' => 'post', 'id' => 'report', 'class' => 'bf', 'files'=> true)) !!}
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('user', trans('report/form.user')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('user', $username, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('report/form.user_placeholder'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('email', trans('report/form.email')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('email', $email, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('report/form.email_placeholder'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('nature', trans('report/form.nature')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('nature', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('report/form.nature_placeholder'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									{!! Form::label('comment', trans('report/form.comment')) !!}
								</div>
								<div class="col-md-6 col-md-offset-3">
									{!! Form::textarea('comment', null, array('class' => 'textarea form-control', 'rows'=>'5', 'placeholder'=>trans('report/form.comment_placeholder'), 'required' => 'required', 'style'=>'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"')) !!}
								</div>
							</div>
						</div>		

						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									{!! Form::label('files', trans('report/form.files')) !!}
								</div>
								<div class="col-md-12">
									<input id="files" name="files[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true" accept="image/*" />
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
<script src="{{ asset('assets/vendors/fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery("#files").fileinput();
});
</script>
@stop
