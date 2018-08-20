@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.add_ad')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/images-upload.css') }}">
	
    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
	<link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
	<link href="{{ asset('assets/vendors/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/ads.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.add_ad')</li>
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
    <div class="wrapper nearme-item">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.add_ad')</h1>
		</div>
		<hr>
        <div class="content">
			@if(TemplateHelper::getSetting('ads_approval'))
				<p class="notice">@lang('front/general.ads_edit_notice')</p>
			@endif
           <div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
			{!! Form::open(array('url' => URL::to('newad'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
				 <div class="row">
                    <div class="col-sm-12">
                        
						<div class="form-group">
							{!! Form::label('title', trans('ads/form.title')) !!}
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('ads/form.title'))) !!}
                        </div>

						<div class="form-group">
							{!! Form::label('phone', trans('ads/form.phone')) !!}
							{!! Form::text('phone', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('ads/form.phone'))) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('url', trans('ads/form.url')) !!}
                            {!! Form::text('url', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('ads/form.url'))) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('position_id', trans('ads/form.position')) !!}
                            {!! Form::select('position_id',$adspositionsForSelet ,null, array('class' => 'form-control select2', 'placeholder'=>trans('ads/form.select-position'))) !!}
                        </div>
						
						<div class="form-group">
							<p class="bold">@lang('front/general.ads_size_notice')</p>
							@forelse($adspositions as $position)
								<p>
									{{ $position->title }}: {{ $position->width }}@lang('front/general.ads_size_width') x {{ $position->height }}@lang('front/general.ads_size_height')
								</p>
							@empty
							@endforelse
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<label>@lang('ads/form.image')</label>
								</div>
								<div class="col-md-12">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
										<div>
											<span class="btn btn-primary btn-file">
												<span class="fileinput-new">@lang('front/general.select_image')</span>
												<span class="fileinput-exists">@lang('front/general.change')</span>
												<input type="file" name="image" id="image" />
											</span>
											<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">@lang('front/general.remove')</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							{!! Form::label('published_from', trans('ads/form.published_from')) !!}
							{!! Form::DateTimeInput('published_from')!!}
						</div>						
						
						<?php /*
						<div class="form-group">
							{!! Form::label('published_to', trans('ads/form.published_to')) !!}
							{!! Form::DateTimeInput('published_to')!!}
						</div>
						*/ ?>
						
						@if(TemplateHelper::isDeveloper())
							<div class="form-group reneval-wrapper">
								@if(count($adrenewoptions) > 0)
									<div class="row">
										<div class="col-lg-12">
											{!! Form::label('renewal', trans('classified/form.renewal_label')) !!}
											<ul class="reneval-period">
												@foreach($adrenewoptions as $loop => $adrenewoption)
													<li>
														{!! Form::radio('renewal', $adrenewoption->id, $loop == 0 ? true : false) !!} {{ TemplateHelper::getDiscountOptionName($adrenewoption->renewal_period_name, $adrenewoption->renewal_discount) }}
													</li>
												@endforeach
											</ul>
										</div>
									</div>
								@endif
							</div>
							<div class="form-group recurring {{ $errors->first('recurring', 'has-error') }}">
								<div class="row">
									<div class="col-md-12 recurring-box">
										<label>{!! Form::checkbox('recurring', 1, true) !!} @lang('ads/form.recurring_checkbox')</label>
										<span class="help-block">{{ $errors->first('recurring', ':message') }}</span>
									</div>
								</div>
							</div>
						@endif
						
						<div class="form-group">
							<button type="submit" class="btn btn-success">@lang('front/general.publish')</button>
							<a href="{!! URL::to('my-account') !!}" class="btn btn-danger">@lang('front/general.discard')</a>
						</div>
                    </div>
				</div>
			{!! Form::close() !!}
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit nearme-->
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>

{!! TemplateHelper::includeDateScript() !!}
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.yesno-value').each(function() {
		jQuery(this).bootstrapSwitch('state');
	});
	
	jQuery("input[type='radio'], input[type='checkbox']").iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue',
		checkboxClass: 'icheckbox_minimal-blue'
	});
	
	jQuery('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = jQuery(this).attr('data-config');
		jQuery('input[name="'+id+'"]').val(+state);
	});
	
	jQuery("#published_from").data('DateTimePicker').minDate(new Date());
	jQuery("#published_from").on("dp.change", function(e) {
		var value = jQuery(this).val();
		
		var date = new Date(value);
		date.setMonth(date.getMonth() + 1);
		jQuery("#published_to").data('DateTimePicker').minDate(date);
		//jQuery("#published_to").datetimepicker("refresh");
	});
});
</script>
@stop