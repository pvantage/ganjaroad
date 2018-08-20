@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.add_nearme')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
	<link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/images-upload.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/nearme.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.add_nearme')</li>
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
		<div class="notice">
			<h5>@lang('nearme/form.add_nearme_top_notice')</h5>
		</div>
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.add_nearme')</h1>
		</div>
		<hr>
        <div class="content">
           <div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
            {!! Form::open(array('url' => URL::to('newnearme'), 'method' => 'post', 'id' => 'nearme', 'class' => 'bf', 'files'=> true)) !!}
				 <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
							<div class="col-md-12">
								{!! Form::label('title', trans('nearme/form.title')) !!}
							</div>
							<div class="col-md-12">
								{!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.title'))) !!}
							</div>
                        </div>
                        <div class="box-body pad">
							<div class="col-md-12">
								{!! Form::label('content', trans('nearme/form.content')) !!}
							</div>
							<div class="col-md-12">
								{!! TemplateHelper::renderWyswingEditor() !!}
							</div>
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('category_id', trans('nearme/form.category')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::select('category_id',$nearmecategory, Request::get('category'), array('class' => 'form-control select2', 'required' => 'required')) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('email', trans('nearme/form.email')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('email', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.email'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('phone', trans('nearme/form.phone')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('phone', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.phone'))) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('url', trans('nearme/form.url')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('url', trans('nearme/form.url_placeholder')) !!}
								</div>
							</div>
						</div>
						
						<div class="address-wrapper">
							<div class="form-group addressbox-description">
								{{ trans('nearme/form.addresstop') }}
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('address1', trans('nearme/form.address')) !!}
									</div>
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::text('address1', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address1'))) !!}
										</div>
										<div class="form-group">
											{!! Form::text('address2', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address2'))) !!}
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('country', trans('nearme/form.country')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::select('country', TemplateHelper::getCountriesList(false), 'US', array('class' => 'form-control select2', 'id' => 'country', 'required' => 'required', 'placeholder' => trans('nearme/form.country'))) !!}
									</div>
								</div>
							</div>
								
							<div class="form-group" id="state-wrapper">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('state', trans('nearme/form.select-state')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::SelectState('state', $nearmecategory, null, array('class' => 'form-control select2', 'id' => 'state', 'placeholder'=>trans('nearme/form.select-state'))) !!}
									</div>
								</div>
							</div>
								
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('city', trans('nearme/form.city')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::text('city', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.city'))) !!}
									</div>
								</div>
							</div>
								
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('zip', trans('nearme/form.zip')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::text('zip', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.zip'))) !!}
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="other_address" id="other_address" value="1" />
										<span class="another-address">@lang('nearme/form.another-address-checkbox')</span>
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group" id="full_address_wrapper" style="display: none;">
							<textarea name="full_address" class="form-control" rows="5"></textarea>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('lattitude', trans('nearme/form.lattitude')) !!}
											{!! Form::text('lattitude', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.lattitude'))) !!}
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('longitude', trans('nearme/form.longitude')) !!}
											{!! Form::text('longitude', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.lattitude'))) !!}
										</div>
									</div>
								</div>
							</div>
                        </div>
						
						<div class="col-md-12">
							<div class="form-group">
								<div class="panel-body map-wrapper">
									<div id="map"></div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('delivery', trans('nearme/form.delivery')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('delivery', null) !!}
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('atm', trans('nearme/form.atm')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('atm', null) !!}
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('min_age', trans('nearme/form.min_age')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::select('min_age', TemplateHelper::getMinAgeSelect(), null, array('class' => 'form-control select2', 'placeholder'=>trans('nearme/form.select'))) !!}
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('wheelchair', trans('nearme/form.wheelchair')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('wheelchair', null) !!}
								</div>
							</div>
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('security', trans('nearme/form.security')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('security', null) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
								{!! Form::label('credit_cards', trans('nearme/form.credit_cards')) !!}
								</div>
								<div class="col-md-6">
								{!! Form::YesNo('credit_cards', null) !!}
								</div>
							</div>
                        </div>
						
						<?php /* maybe we will go back to this, for now required
						@if(TemplateHelper::isDeveloper())
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('recurring', trans('nearme/form.recurring')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::YesNo('recurring') !!}
									</div>
								</div>
							</div>
						@endif
						*/ ?>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label>@lang('nearme/form.image')</label>
								</div>
								<div class="col-md-6">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
											@if(Form::old('old_image'))
												<img src="{{ asset('uploads/temp/'.Form::old('old_image')) }}" class="img-responsive" />
												<input type="hidden" name="old_image" value="{{ Form::old('old_image') }}" />
											@else
											@endif
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
										<div>
											<span class="btn btn-primary btn-file">
												<span class="fileinput-new">@lang('front/general.select_image')</span>
												<span class="fileinput-exists">@lang('front/general.change')</span>
												<input type="file" name="image" id="image" value="{{ Form::old('old_image') }}" />
											</span>
											<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">@lang('front/general.remove')</span>
										</div>
									</div>
								</div>
							</div>
                        </div>
						
						{!! Form::label('hours', trans('nearme/form.hours')) !!}
						{!! Form::Hours('hours', null) !!}
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('facebook', trans('nearme/form.facebook')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('facebook', trans('nearme/form.facebook'), false, false) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('instagram', trans('nearme/form.instagram')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('instagram', trans('nearme/form.instagram'), false, false) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('twitter', trans('nearme/form.twitter')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('twitter', trans('nearme/form.twitter'), false, false) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-12 col-lg-3">
									{!! Form::label('first_time', trans('nearme/form.first_time')) !!}
								</div>
								<div class="col-md-12 col-lg-6">
									{!! Form::textarea('first_time', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.first_time'))) !!}
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<p>@lang('nearme/form.menu')</p>
								</div>
								<div class="col-md-12">
									{!! Form::NearmeItems('nearmeitems', null) !!}
								</div>
							</div>
						</div>
						
						<div id="actions">
							<div class="form-group">
								<div class="row">
									<div class="col-lg-12">
										<p>@lang('nearme/form.upload_notice')</p>
										<p>@lang('front/general.upload_notice', ['width' => TemplateHelper::getSetting('global_image_width'), 'height' => TemplateHelper::getSetting('global_image_height')])</p>
										<!-- The fileinput-button span is used to style the file input field as button -->
										<span class="btn btn-success fileinput-button">
											<i class="glyphicon glyphicon-plus"></i>
											<span>@lang('front/general.add_files')</span>
										</span>
										<button class="btn btn-primary start" type="submit">
											<i class="glyphicon glyphicon-upload"></i>
											<span>@lang('front/general.start_upload')</span>
										</button>
										<button class="btn btn-warning cancel" type="reset">
											<i class="glyphicon glyphicon-ban-circle"></i>
											<span>@lang('front/general.cancel_upload')</span>
										</button>
									</div>
								</div>
							</div>
								
							<div class="form-group">
								<div class="row">
									<div class="col-lg-12">
										<!-- The global file processing state -->
										<span class="fileupload-process">
											<div aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress progress-striped active" id="total-progress" style="opacity: 0">
												<div data-dz-uploadprogress="" style="width:0%;" class="progress-bar progress-bar-success"></div>
											</div>
										</span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="table table-striped files" id="previews">
							<div id="images" class="file-row">
								<!-- This is used as the file preview template -->
								<div>
									<span class="preview"><img data-dz-thumbnail /></span>
								</div>
								<div>
									<p class="name" data-dz-name></p>
									<strong class="error text-danger" data-dz-errormessage></strong>
								</div>
								<div>
									<p class="size" data-dz-size></p>
									<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
										<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
									</div>
									<div class="output"></div>
								</div>
								<div>
									<button class="btn btn-primary start">
										<i class="glyphicon glyphicon-upload"></i>
										<span>@lang('front/general.start')</span>
									</button>
								  
									<button data-dz-remove class="btn btn-warning cancel">
										<i class="glyphicon glyphicon-ban-circle"></i>
										<span>@lang('front/general.cancel')</span>
									</button>
									<button data-dz-remove class="btn btn-danger delete">
										<i class="glyphicon glyphicon-trash"></i>
										<span>@lang('front/general.delete')</span>
									</button>
								</div>
							</div>
						</div>
						
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
										<label>{!! Form::checkbox('recurring', 1, true) !!} @lang('nearme/form.recurring_checkbox')</label>
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
				
				@if(Form::old('images'))
					@foreach(Form::old('images') as $image)
						<input type="hidden" name="images[]" value="{{ $image }}" />
					@endforeach
				@endif
				
			{!! Form::close() !!}
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit nearme-->
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>

<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>

<script type="text/javascript">
var prefix = 1;
var is_upload = false;
var lat = {!! TemplateHelper::getDefaultLattitude() !!};
var lng = {!! TemplateHelper::getDefaultLongitude() !!};
	
var map = new GMaps({
	el: '#map',
	lat: lat,
	lng: lng
});

jQuery( document ).ready(function() {
	prefix = jQuery('#nearmeitems tbody tr').length;

    jQuery("input[type='radio'], input[type='checkbox']").iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

	
	jQuery('#other_address').on('ifChanged', function(event){
		if(jQuery(this).is(":checked")) {
			jQuery('#full_address_wrapper').fadeIn();
		} else {
			jQuery('#full_address_wrapper').fadeOut();
		}
	});
	
	jQuery(".url-type").on("click", function(){
		var wrapper = jQuery(this).closest('.url-wrapper');
		wrapper.find('.url-type').removeClass('selected');
		jQuery(this).addClass('selected');
		
		var url_type = jQuery(this).text();
		var value = wrapper.find('.temp_url').val();
		jQuery('#url_button').text(url_type);
		
		if(value) wrapper.find('.real-value').val(url_type + value);
		else wrapper.find('.real-value').val('');
	});
	
	jQuery(".temp_url").change(function() {
		var wrapper = jQuery(this).closest('.url-wrapper');
		var url_type = wrapper.find('.url-type.selected').text();
		var value = jQuery(this).val();

		if(value) wrapper.find('.real-value').val(url_type + value);
		else wrapper.find('.real-value').val('');
	});
	
	var previewNode = document.querySelector("#images");
	previewNode.id = "";
	var previewTemplate = previewNode.parentNode.innerHTML;
	previewNode.parentNode.removeChild(previewNode);
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone(document.body, {
		url: "{{ URL::to('uploadimage') }}",
		thumbnailWidth: 80,
		thumbnailHeight: 80,
		maxFiles: {{ TemplateHelper::getSetting('nearme_images_limit') }},
		parallelUploads: 1,
		previewTemplate: previewTemplate,
		autoQueue: false,
		autoProcessQueue: true,
		acceptedFiles: 'image/*',
		previewsContainer: "#previews",
		clickable: ".fileinput-button",
		init: function () {
			@if(Form::old('images'))
				@foreach(Form::old('images') as $image)
					<?php
					$dir = TemplateHelper::getTempImageDir();
					$path = public_path().$dir.$image;
					if(!$path) continue;
					$ext = pathinfo($path, PATHINFO_EXTENSION);
					$filesize = filesize($path);
					?>

					var mockFile = { name: "{{ $image }}", size: {{ $filesize }}, type: 'image/{{ $ext }}', accepted: true };
					this.options.addedfile.call(this, mockFile);
					this.options.complete.call(this, mockFile);
					this.options.thumbnail.call(this, mockFile, "{{ asset('uploads/temp/'.$image) }}");
					this.files.push(mockFile);
				@endforeach
			@endif
		}
	});
	
	myDropzone.on("addedfile", function(file) {
		file.previewElement.querySelector(".start").onclick = function() { 
			is_upload = true;
			myDropzone.enqueueFile(file); 
		};
	});

	myDropzone.on("totaluploadprogress", function(progress) {
		document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
	});

	myDropzone.on("sending", function(file, xhr, formData) {
		is_upload = true;
		jQuery('.output').text('');
		document.querySelector("#total-progress").style.opacity = "1";
		file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
		formData.append('_token', '{{ csrf_token() }}');
		formData.append('_method', 'post');
	});

	myDropzone.on("queuecomplete", function(progress) {
		is_upload = false;
		document.querySelector("#total-progress").style.opacity = "0";
	});
	
	myDropzone.on("removedfile", function(file) {
		var input = jQuery('input[value="'+file.name+'"]');
		if(input.length) {
			input.remove();
		} else {
			var input = jQuery('input[data-image="'+file.name+'"]');
			if(input.length) {
				input.remove();
			}
		}
	});
	
	myDropzone.on("success", function(file, responseText) {
		if(!responseText.success) {
			jQuery('.output').text(responseText.msg);
		} else {
			jQuery('<input>').attr({
				type: 'hidden',
				value : responseText.img_name,
				name: 'images[]',
				'data-image': file.name
			}).appendTo('form#nearme');
		}
	});

	document.querySelector("#actions .start").onclick = function() {
		is_upload = true;
		myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
	};
	
	document.querySelector("#actions .cancel").onclick = function() {
		myDropzone.removeAllFiles(true);
	};
	
	jQuery('form#nearme').submit(function( event ) {
        if (is_upload) {
            event.preventDefault();
            event.stopPropagation();
		}
	   
        return;
    });
	
	if(jQuery('#country').val() != 'US') jQuery('#state-wrapper').fadeOut();
	jQuery('.textarea').summernote();
	
	var marker = map.addMarker({
		lat: lat,
		lng: lng,
		draggable:true,
		animation: google.maps.Animation.DROP
	});

	marker.addListener('dragend', handleDrag);
	
	jQuery('.yesno-value').each(function() {
		jQuery(this).bootstrapSwitch('state');
	});
	
	jQuery('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = jQuery(this).attr('data-config');

		jQuery('input[name="'+id+'"]').val(+state);
	});
	
	jQuery('.map-input').on('change', function(){
		updateMap();
	});
	
	jQuery("#country").select2({
		placeholder: "@lang('nearme/form.select_country')",
		theme: 'bootstrap',
		allowClear: false,
		formatResult: format,
		formatSelection: format,
		width: 'resolve',
		templateResult: format,
		escapeMarkup: function (m) {
			return m;
		},
	});
	jQuery("#country").trigger('change');
	
	jQuery('#country').on("select2:selecting", function(e) {
		if(jQuery(this).val() == 'US') {
			jQuery('#state-wrapper').fadeOut();
		} else {
			jQuery('#state-wrapper').fadeIn();
		}
	});
	
	var table = jQuery('#nearmeitems').DataTable({
		responsive: true,
		language: {
			"emptyTable": "@lang('nearme/table.no_menu_data_available')"
		},
		bPaginate: false,
		paging: false,
        ordering: false,
        info: false,
		searching: false,
		columnDefs: [{
			targets  : 'no-sort',
			orderable: false,
			order: []
		}]
	});
	
	var table_row = jQuery('{!! Form::NearmeBlankItem() !!}');
	jQuery('#addtablerow').on('click', function() {
		prefix = table.rows().count();
		var table_clone = table_row.clone();
		table_clone.find('input').each(function() {
		   this.name = this.name.replace(/nearmeitems\[\d+\]/, 'nearmeitems[' + prefix + ']');   
		});
		
		table_clone.find('textarea').each(function() {
		   this.name = this.name.replace(/nearmeitems\[\d+\]/, 'nearmeitems[' + prefix + ']');   
		});
		
		table_clone.find('select').each(function() {
		   this.name = this.name.replace(/nearmeitems\[\d+\]/, 'nearmeitems[' + prefix + ']');   
		});
		
		//jQuery('#nearmeitems tbody').append(table_clone[0].outerHTML);
		table.row.add(table_clone[0]).draw();
	});
	
	jQuery(document).on('click', '.remove_row', function() {
		if(jQuery(this).closest('tr').prev('tr.parent').length > 0) {
			var row = jQuery(this).closest('tr').prev('tr.parent');
		} else var row = jQuery(this).closest('tr');
		
		table.row(row).remove().draw(false);
		
	});
});

function updateMap() {
	var address = [];
		
	var address_line = jQuery('[name=address1]').val() + ' ' + jQuery('[name=address2]').val();
	if(address_line && address_line != ' ') address.push(address_line);

	var city = jQuery('[name=city]').val();
	if(city && city != '') address.push(city);
		
	var zip = jQuery('[name=zip]').val();
	if(zip && zip != '') address.push(zip);
		
	var country = jQuery('[name=country] option:selected').text();
	if(country && country != '') address.push(country);

	var str_address = address.join(', ');
	if(str_address) {
		GMaps.geocode({
			address: str_address,
			callback: function(results, status) {
			if (status == 'OK') {
				var latlng = results[0].geometry.location;
				map.setCenter(latlng.lat(), latlng.lng());
				map.removeMarkers();
				var marker = map.addMarker({
					lat: latlng.lat(),
					lng: latlng.lng(),
					draggable:true,
					animation: google.maps.Animation.DROP
				});
				
				marker.addListener('dragend', handleDrag);
				updateLatLng(latlng.lat(), latlng.lng());
			}
		  }
		});
	}
}

function format(state) {
    if (!state.id) return state.text; // optgroup
    return "<img class='flag' src='{{ asset('assets/images/countries_flags') }}/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
}

function handleDrag(event) {
	updateLatLng(event.latLng.lat(), event.latLng.lng());
}
	
function updateLatLng(new_lat, new_lng) {
	jQuery('#lattitude').val(new_lat);
	jQuery('#longitude').val(new_lng);
}
</script>
@stop