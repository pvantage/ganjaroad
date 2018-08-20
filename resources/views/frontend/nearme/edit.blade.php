@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.edit_nearme')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
	<link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
	<link href="{{ asset('assets/vendors/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/images-upload.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/nearme.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.edit_nearme')</li>
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
			<h1>@lang('front/general.edit_nearme')</h1>
		</div>
		<hr>
        <div class="content">
			<?php /*
			@if(TemplateHelper::getSetting('nearme_approval'))
				<p class="notice">@lang('front/general.nearme_edit_notice')</p>
			@endif
			*/ ?>
           <div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
            {!! Form::model($nearme, array('url' => URL::to('nearme/'.$nearme->id.'/edit'), 'method' => 'post', 'id' => 'nearme', 'class' => 'bf', 'files'=> true)) !!}
				<input type="hidden" name="_method" value="PUT">
				 <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
							<div class="col-md-12">
								{!! Form::label('title', trans('nearme/form.title')) !!}
							</div>
							<div class="col-md-12">
								{!! Form::text('title', $nearme->title, array('id' => 'nearme', 'class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.title'))) !!}
							</div>
                        </div>
                        <div class='box-body pad'>
							<div class="col-md-12">
								{!! Form::label('content', trans('nearme/form.content')) !!}
							</div>
							<div class="col-md-12">
								{!! TemplateHelper::renderWyswingEditor($nearme->content) !!}
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('active', trans('nearme/form.published')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('active', $nearme->active) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('category_id', trans('nearme/form.category')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::select('category_id', $nearmecategory, $nearme->category_id, array('class' => 'form-control select2', 'required' => 'required')) !!}
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
									{!! Form::UrlInput('url', trans('nearme/form.url_placeholder'), $nearme->url) !!}
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
											{!! Form::text('address1', $nearme->address1, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address1'))) !!}
										</div>
										<div class="form-group">
											{!! Form::text('address2', $nearme->address2, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address2'))) !!}
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
										{!! Form::select('country', TemplateHelper::getCountriesList(false), $nearme->country, array('class' => 'form-control select2', 'id' => 'country', 'required' => 'required', 'placeholder'=>trans('nearme/form.country'))) !!}
									</div>
								</div>
							</div>

							<div class="form-group" id="state-wrapper">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('state', trans('nearme/form.select-state')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::SelectState('state', $nearmecategory, $nearme->state, array('class' => 'form-control select2', 'id' => 'state', 'placeholder'=>trans('nearme/form.select-state'))) !!}
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('city', trans('nearme/form.city')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::text('city', $nearme->city, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.city'))) !!}
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('zip', trans('nearme/form.zip')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::text('zip', $nearme->zip, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.zip'))) !!}
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('last_updated', trans('nearme/form.last_update')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::label('last_updated_value', TemplateHelper::nearmeEditFormatDate($nearme->updated_at)) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="other_address" value="1" @if($nearme->other_address) checked @endif />
										<span class="another-address">@lang('nearme/form.another-address-checkbox')</span>
										<input type="hidden" name="other_address" value="{{ $nearme->other_address }}" />
									</label>
								</div>
							</div>
						</div>

						<div class="form-group" id="full_address_wrapper" @if(!$nearme->other_address) style="display: none;" @endif >
							<textarea name="full_address" class="form-control" rows="5">{{ $nearme->full_address }}</textarea>
						</div>

						<div class="form-group">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('lattitude', trans('nearme/form.lattitude')) !!}
											{!! Form::text('lattitude', $nearme->lattitude, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.lattitude'))) !!}
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('longitude', trans('nearme/form.longitude')) !!}
											{!! Form::text('longitude', $nearme->longitude, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.lattitude'))) !!}
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
									{!! Form::YesNo('delivery', $nearme->delivery) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('atm', trans('nearme/form.atm')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('atm', $nearme->atm) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('min_age', trans('nearme/form.min_age')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::select('min_age', TemplateHelper::getMinAgeSelect(), $nearme->min_age, array('class' => 'form-control select2', 'placeholder'=>trans('nearme/form.select'))) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('wheelchair', trans('nearme/form.wheelchair')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('wheelchair', $nearme->wheelchair) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('security', trans('nearme/form.security')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('security', $nearme->security) !!}
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
								{!! Form::label('credit_cards', trans('nearme/form.credit_cards')) !!}
								</div>
								<div class="col-md-6">
								{!! Form::YesNo('credit_cards', $nearme->credit_cards) !!}
								</div>
							</div>
                        </div>

						@if(TemplateHelper::isDeveloper())
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										{!! Form::label('recurring', trans('nearme/form.recurring')) !!}
									</div>
									<div class="col-md-6">
										{!! Form::YesNo('recurring', $nearme->recurring) !!}
									</div>
								</div>
							</div>
						@endif

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									<label>@lang('nearme/form.image')</label>
								</div>
								<div class="col-md-6">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
											@if($nearme->image)
												<img src="{{ asset('uploads/nearme/'.$nearme->id.'/'.$nearme->image) }}" class="img-responsive" />
											@else
											@endif
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

						{!! Form::label('hours', trans('nearme/form.hours')) !!}
						{!! Form::Hours('hours', $nearme->hours) !!}

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('facebook', trans('nearme/form.facebook')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('facebook', trans('nearme/form.facebook'), $nearme->facebook, false) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('instagram', trans('nearme/form.instagram')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('instagram', trans('nearme/form.instagram'), $nearme->instagram, false) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('twitter', trans('nearme/form.twitter')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::UrlInput('twitter', trans('nearme/form.twitter'), $nearme->twitter, false) !!}
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-md-12 col-lg-3">
									{!! Form::label('first_time', trans('nearme/form.first_time')) !!}
								</div>
								<div class="col-md-12 col-lg-6">
									{!! Form::textarea('first_time', $nearme->first_time, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.first_time'))) !!}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<p>@lang('nearme/form.menu')</p>
								</div>
								<div class="col-md-12">
									@if($nearme->items()->count())
										{!! Form::NearmeItems('nearmeitems', $nearme) !!}
									@else
										{!! Form::NearmeItems('nearmeitems', null) !!}
									@endif
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

						<div class="table table-striped" class="files" id="previews">
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

						@if($nearme->recurring == 0)
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
										<label>{!! Form::checkbox('recurring', 1, true) !!} @lang('nearme/form.recurring_checkbox', ['default' => Lang::get('classified/form.renewal_every_2_weeks')])</label>
										<span class="help-block">{{ $errors->first('recurring', ':message') }}</span>
									</div>
								</div>
							</div>
						@endif

						<div class="form-group">
							<button type="submit" class="btn btn-success">@lang('front/general.publish')</button>
							@if($nearme->recurring == 1 && $nearme->paid == 1)
								@if(!empty($nearme->vault_id))
									<a href="{{ route('updatevault', ['vault_id' => $nearme->vault_id]) }}" class="btn btn-success">Update Credit Card details</a>
								@endif
							@endif
							<a href="{!! URL::to('my-account') !!}" class="btn btn-danger">@lang('front/general.discard')</a>
						</div>
                    </div>
				</div>

				@forelse($nearme->images as $image)
					<input type="hidden" name="images[]" value="{{ $image->image }}" />
				@empty
				@endforelse

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
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>

<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>

<script type="text/javascript">
var is_upload = false;
var prefix = 1;
var lat = {{ $nearme->lattitude }};
var lng = {{ $nearme->longitude }};

var map = new GMaps({
	el: '#map',
	lat: lat,
	lng: lng
});

jQuery( document ).ready(function() {
	prefix = jQuery('#nearmeitems tbody tr').length;

	jQuery("input[type='checkbox']").iCheck({
		checkboxClass: 'icheckbox_minimal-blue'
	});

	jQuery('#other_address').on('ifChanged', function(event){
		if(jQuery(this).is(":checked")) {
			jQuery('#full_address_wrapper').fadeIn();
			jQuery('input[name=other_address]').val(1);
		} else {
			jQuery('#full_address_wrapper').fadeOut();
			jQuery('input[name=other_address]').val(0);
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

	if(jQuery('#country').val() != 'US') jQuery('#state-wrapper').fadeOut();

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
			@if($nearme->images)
				@foreach($nearme->images as $image)
					var mockFile = { name: "{{ $image->image }}", size: {{ $image->size }}, type: 'image/{{ $image->imagetype }}', accepted: true };
					this.options.addedfile.call(this, mockFile);
					this.options.complete.call(this, mockFile);
					this.options.thumbnail.call(this, mockFile, "{{ asset('uploads/nearme/'.$image->item_id) }}/{{ $image->image }}");
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

	var table = jQuery('#nearmeitems').DataTable({
		responsive: true,
		language: {
			"emptyTable": "@lang('nearme/table.no_menu_data_available')"
		},
		bPaginate: false,
		paging: false,
        ordering: false,
        info: false,
		searching: true,
		columnDefs: [{
			targets  : 'no-sort',
			orderable: false,
			order: []
		}],
		@if($nearme->items()->count())
		initComplete: function () {
            this.api().columns().every( function (index) {
                var column = this;
				if(index == 1){
					var select = jQuery('<select class="filter"><option value=""></option></select>')
						.appendTo( jQuery(column.header()) )
						.on( 'change', function () {
							var val = jQuery.fn.dataTable.util.escapeRegex(
								jQuery(this).val()
							);

							column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
						});

						select.append( '<option value="1">@lang('general.yes')</option>' );
						select.append( '<option value="0">@lang('general.no')</option>' );
				} else if(index == 2) {
					var column = this;
					var select = jQuery('<select><option value=""></option></select>')
						.appendTo( jQuery(column.header()) )
						.on( 'change', function () {

							// Escape the expression so we can perform a regex match
							var val = jQuery.fn.dataTable.util.escapeRegex(
								jQuery(this).val()
							);

							column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
						});


					var filters = true;
					column.data().unique().sort().each( function ( d, j ) {
						if(d && filters) {
							var options = jQuery(d).find('select option');
							options.each(function() {
								select.append( '<option value="'+jQuery(this).val()+'">'+jQuery(this).text()+'</option>' );
							});
							filters = false;
						}
					});
				}
            });
        }
		@endif
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
		table.row.add(table_clone).draw();
	});

	jQuery(document).on('click', '.remove_row', function() {
		if(jQuery(this).closest('tr').prev('tr.parent').length > 0) {
			var row = jQuery(this).closest('tr').prev('tr.parent');
		} else var row = jQuery(this).closest('tr');

		table.row(row).remove().draw(false);

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

	jQuery('#country').on("select2:selecting", function(e) {
		if(jQuery(this).val() == 'US') {
			jQuery('#state-wrapper').fadeOut();
		} else {
			jQuery('#state-wrapper').fadeIn();
		}
	});

	updateMap();
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
