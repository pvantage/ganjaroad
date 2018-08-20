@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('classified/title.edit')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/vendors/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css') }}" rel="stylesheet" type="text/css" media="all" />

	<link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/classifieds.css') }}">
	<link href="{{ asset('assets/vendors/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/images-upload.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tier.css') }}">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('classified/title.edit')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('classified/title.classified')</a>
        </li>
        <li class="active">@lang('classified/title.edit')</li>
    </ol>
</section>
<!--section ends-->

<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
			<div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>

           {!! Form::model($classified, array('url' => URL::to('admin/classified/' . $classified->id.'/edit'), 'id' => 'classified', 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
                <div class="row">
                    <div class="col-sm-8">

                        <div class="form-group">
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('classified/form.title'))) !!}
                        </div>
                        <div class='box-body pad'>
                            {!! TemplateHelper::renderWyswingEditor($classified->content) !!}
                        </div>

						<div class="form-group">
							<div class="col-md-12">
								<div class="row">
									<div class="form-group">
										{!! Form::label('map_address', trans('classified/form.map_address')) !!}
										{!! Form::text('map_address', $classified->map_address, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('classified/form.map_address'))) !!}
									</div>
								</div>

								<div class="row address-switch">
									<div class="form-group">
										<div class="col-sm-6">
											{!! Form::label('hide_map', trans('classified/form.hide_map')) !!}
										</div>
										<div class="col-sm-6">
											{!! Form::YesNo('hide_map', null) !!}
										</div>
									</div>
								</div>

								<div class="address-wrapper row" @if(!$classified->hide_map) style="display:none" @endif>
									<p class="address-notice">@lang('front/general.address_notice')</p>
									@foreach ($classifiedfields as $extrafield)
										@if($extrafield->field_type == 'address')
											<div class="form-group">
												{!! Form::ExtraFields($extrafield->code, $extrafield, $classifiedfieldsvalues, array(), true) !!}
											</div>
										@endif
									@endforeach
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('lattitude', trans('classified/form.lattitude')) !!}
											{!! Form::text('lattitude', $classified->lattitude, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('classified/form.lattitude'))) !!}
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('longitude', trans('classified/form.longitude')) !!}
											{!! Form::text('longitude', $classified->longitude, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('classified/form.lattitude'))) !!}
										</div>
									</div>
								</div>
							</div>
                        </div>

						<div class="form-group">
							<div class="panel-body map-wrapper">
								<div id="map"></div>
							</div>
						</div>

						<div id="category_choose">
							{{--*/ $categories = $classified->deafult_categories_ids(); $i = 0; /*--}}
							@if($categories)
								@foreach($categories as $category)
									{{--*/ $select = TemplateHelper::renderCategoryChildren($category, $i) /*--}}
									@if($select)
										{!! $select !!}
									@else

									@endif
									{{--*/ ++$i; /*--}}
								@endforeach
							@else
								<div class="category-select-wrapper form-group">
									{!! Form::select('categories[]', $classifiedcategory, "", array('id' => 'category', 'data-level' => '0', 'class' => 'form-control select2 category-select', 'autocomplete' => 'off', 'placeholder'=> trans('front/general.classified_category_placeholder'))) !!}
								</div>
							@endif
                        </div>

						<div class="multicity" @if(!$classified->multicategory->count()) style="display:none" @endif>
							<div class="row">
								<div class="form-group">
									<div class="col-sm-6">
										{!! Form::label('multicity', trans('classified/form.multicity')) !!}
									</div>
									<div class="col-sm-6">
										{{--*/ if($classified->multicategory->count()) $selected = 1; else $selected = 0; /*--}}
										{!! Form::YesNo('multicity', $selected) !!}
									</div>
								</div>
							</div>
							<div class="multicity-wrapper" @if(!$classified->multicategory->count()) style="display:none" @endif>
								<div class="dropdowns">
									@if($cities = $classified->multicategory)
										{{--*/ $options = TemplateHelper::getClassifiedCitiesList(end($categories)) /*--}}
										@foreach($cities as $city)
											<div class="col-md-12 city-dropdown">
												<div class="row">
													<div class="form-group">
														<div class="col-md-8">
															<select class="selectpicker" name="multicategory[]" data-live-search="true">
																<option value="{{ $city->category_id }}" selected="selected">{{ $options[$city->category_id] }}</option>
															</select>
														</div>
														<div class="col-md-4 text-right">
															<a href="javascript:void(0)" class="remove-city"><i class="fa fa-times-circle"></i> @lang('classified/form.remove_city')</a>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									@endif
								</div>

								<div class="col-md-12">
									<div class="row">
										<div class="form-group">
											<div class="actions text-right">
												<a href="javascript:void(0)" class="add-city">
													<i class="fa fa-plus-circle" aria-hidden="true" title="@lang('classified/form.add_city')"></i> @lang('classified/form.add_city')
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="actions">
							<div class="form-group">
								<div class="row">
									<div class="col-lg-12">
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

                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
						@if($classified->canBeRefunded())
							<div class="form-group">
								<a href="#" data-toggle="modal"
									data-target="#refund" title="@lang('classified/form.give_refund')" class="btn btn-danger">
									@lang('classified/form.give_refund')
								</a>
							</div>
						@endif

						@if($classified->isReported())
							<div class="form-group">
								{!! Form::label('isreported', trans('classified/form.isreported')) !!}
							</div>
							<div class="form-group">
								<a href="{{ route('confirm/reporteditems', $classified->id) }}" data-toggle="modal"
									data-target="#approve_confirm" title="@lang('reporteditems/table.approve')" class="btn btn-danger">
									@lang('classified/form.approve')
								</a>
							</div>
						@endif

						@if($classified->issues()->count())
							<div class="form-group">
								{!! Form::label('issues', trans('classified/form.issues')) !!}
							</div>
							@foreach ($issues as $issue)
							<div class="form-group">
								{!! $issue->comment !!} @if( $issue->code)<span class="issue-code">({!! $issue->code !!})</span>@endif
							</div>
							@endforeach
							<div class="form-group">
								<a href="{{ route('confirm/issues', $classified->id) }}" data-toggle="modal"
									data-target="#approve_confirm" title="@lang('issues/table.approve')" class="btn btn-danger">
									@lang('classified/form.issues_approve')
								</a>
							</div>
						@endif

						<div class="form-group">
							{!! $classified->getAdminAgreementTxt() !!}
						</div>

						<div class="form-group">
							{!! Form::label('published', trans('classified/form.published')) !!}
							{!! Form::YesNo('published', $classified->published) !!}
                        </div>

						<div class="form-group">
							{!! Form::label('active', trans('classified/form.active')) !!}
							{!! Form::YesNo('active', $classified->active) !!}
							<div class="form-group">
								@lang('classified/form.active_comment')
							</div>
                        </div>

						<div class="form-group">
                            {!! Form::label('paid', trans('classified/form.paid')) !!}
							{!! Form::YesNo('paid', $classified->paid) !!}
                        </div>

						<div class="form-group">
							{!! Form::label('active_to', trans('classified/form.active_to')) !!}
							{!! Form::DateTimeInput('active_to')!!}
						</div>

						<div class="form-group">
							@if(TemplateHelper::getSetting('classified_approval'))
								{!! Form::label('approved', trans('classified/form.approved')) !!}
								{!! Form::YesNo('approved', $classified->approved) !!}

								<div class="form-group">
									@lang('classified/form.approved_comment')
								</div>
							@endif
                        </div>

						@foreach ($classifiedfields as $extrafield)
							@if($extrafield->field_type == 'address')
							@continue
							@endif
							<div class="form-group">
								{!! Form::ExtraFields($extrafield->code, $extrafield, $classifiedfieldsvalues, array(), true) !!}
							</div>
						@endforeach

						<?php /*
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									{!! Form::label('recurring', trans('nearme/form.recurring')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::YesNo('recurring', $classified->recurring) !!}
								</div>
							</div>
                        </div>
						*/ ?>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('classified/form.publish')</button>
                            <a href="{!! URL::to('admin/classified') !!}"
                               class="btn btn-danger">@lang('classified/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                <!-- /.row -->
			{!! Form::close() !!}

			@include('admin/layouts/item_transaction_history', ['item' => $classified])

        </div>
    </div>
    <!--main content ends-->
</section>

<div class="modal fade" id="approve_confirm" tabindex="-1" role="dialog" aria-labelledby="item_approve_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
	</div>
</div>

@if($classified->canBeRefunded())
	<div class="modal fade" id="refund" tabindex="-2" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="refund_label">@lang('classified/form.give_refund')</h4>
				</div>
				<div class="modal-body">
					{!! Form::open(array('url' => route('refund', ['type' => 'classifieds', 'item_id' => $classified->id, 'transaction_id' => $last_transaction->payment->transaction_id]), 'method' => 'post', 'class' => 'bf')) !!}
						<div class="form-group">
							{!! Form::text('amount', TemplateHelper::getItemMaxRefund($last_transaction->payment->transaction_id, $classified), array('id' => 'refund_amount', 'placeholder'=> trans('classified/form.amount_to_refund'))) !!}
						</div>
						<button type="submit" class="btn btn-success">@lang('classified/form.refund')</button>
						<a href="#" class="btn btn-danger" data-dismiss="modal">@lang('classified/form.discard')</a>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endif

@stop
{{-- page level scripts --}}
@section('footer_scripts')

<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js') }}" ></script>

<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script type="text/javascript">
var is_upload = false;
var last_category = 0;
jQuery( document ).ready(function() {

	@if($classified->canBeRefunded())
		jQuery("#refund_amount").TouchSpin({
			min: 0,
			max: {{ TemplateHelper::getItemMaxRefund($last_transaction->payment->transaction_id, $classified) }},
			step: 0.1,
			decimals: 2,
			boostat: 5,
			maxboostedstep: 10,
			prefix: '{!! TemplateHelper::getCurrencySymbol() !!}'
		});
	@endif

	jQuery('.textarea').summernote();

	jQuery('.selectpicker').selectpicker();

	jQuery("input[type='radio']").iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue'
	});

	jQuery('.yesno-value').each(function() {
		jQuery(this).bootstrapSwitch('state');
	});

	jQuery('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = jQuery(this).attr('data-config');
		if(jQuery(this).closest('.multicity').length){
			if(state) {
				if(!last_category) {
					showNotice("@lang('classified/form.select_category_first')");
					jQuery(this).bootstrapSwitch('state', !state, true);
				} else {
					if(!jQuery('select[name^="multicity"]').length) addCity(last_category);
					jQuery('.multicity-wrapper').show();
				}
			} else jQuery('.multicity-wrapper').hide();
		} else if(jQuery(this).closest('.address-switch').length) {
			if(state) {
				jQuery('.address-wrapper').show();
			} else {
				jQuery('.address-wrapper').hide();
			}
		}
		jQuery('input[name="'+id+'"]').val(+state);
	});

	var previewNode = document.querySelector("#images");
	previewNode.id = "";
	var previewTemplate = previewNode.parentNode.innerHTML;
	previewNode.parentNode.removeChild(previewNode);
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone(document.body, {
		url: "{{ URL::to('admin/uploadimage') }}",
		thumbnailWidth: 80,
		thumbnailHeight: 80,
		maxFiles: {{ TemplateHelper::getSetting('classifieds_images_limit') }},
		parallelUploads: 1,
		previewTemplate: previewTemplate,
		autoQueue: false,
		autoProcessQueue: true,
		acceptedFiles: 'image/*',
		previewsContainer: "#previews",
		clickable: ".fileinput-button",
		init: function () {
			@if($classified->images)
				@foreach($classified->images as $image)
					var mockFile = { name: "{{ $image->image }}", size: {{ $image->size }}, type: 'image/{{ $image->imagetype }}', accepted: true };
					this.options.addedfile.call(this, mockFile);
					this.options.complete.call(this, mockFile);
					this.options.thumbnail.call(this, mockFile, "{{ URL::to('uploads/classified') }}/{{ $classified->id }}/{{ $image->image }}");
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
			}).appendTo('form#classified');
		}
	});

	document.querySelector("#actions .start").onclick = function() {
		is_upload = true;
		myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
	};

	document.querySelector("#actions .cancel").onclick = function() {
		myDropzone.removeAllFiles(true);
	};

	jQuery('form#classified').submit(function( event ) {
        if (is_upload) {
            event.preventDefault();
            event.stopPropagation();
		}

        return;
    });

	var lat = @if($classified->lattitude) {{ $classified->lattitude }} @else {{ TemplateHelper::getDefaultLattitude() }} @endif;
	var lng = @if($classified->longitude) {{ $classified->longitude }} @else {{ TemplateHelper::getDefaultLongitude() }} @endif;

	var map = new GMaps({
		el: '#map',
		lat: lat,
		lng: lng
    });

	var marker = map.addMarker({
		lat: lat,
		lng: lng,
		draggable:true,
		animation: google.maps.Animation.DROP
	});

	marker.addListener('dragend', handleDrag);


	jQuery('.map-input').on('change', function(){
		var address = jQuery(this).val();
		if(address) {
			GMaps.geocode({
				address: address,
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
	});


	jQuery(document).on('change', '.category-select', function(){
		category_id = jQuery(this).val();
		var level = jQuery(this).attr('data-level');

		if(category_id && isInt(category_id)) {
			jQuery.ajax({
				url: '{!! URL::to('admin/classified/newclassified.category') !!}',
				type: 'post',
				data: {'parent': category_id, '_token': jQuery('input[name=_token]').val()},
				success: function(data){
					var response = data.data;
					var levels = jQuery('.category-select').length;

					jQuery('.category-select-wrapper').each(function(index, option) {
						if(index > level) {
							jQuery(this).fadeOut();
							jQuery(this).remove();
						}
					});

					if(!data.recordsTotal) {
						last_category = category_id;
						canShowCity(last_category);
					}

					if(Object.keys(response).length) {
						var select = '<div class="form-group category-select-wrapper"><select name="categories[]" data-level=' + levels + ' class="form-control select2 category-select">';

						select += '<option selected="selected">{{ trans('front/general.classified_category_placeholder') }}</option>'

						jQuery(response).each(function(index, option) {
							select += '<option value="'+option.id+'">'+option.title+'</option>';
						});
						select += '</select></div>';

						jQuery('#category_choose').append(select);
					}
				}
			});
		}
	});

	jQuery('.add-city').on('click', function(){
		if(!last_category) {
			showNotice("@lang('classified/form.select_category_first')");
		} else {
			addCity(last_category);
		}

	});

	jQuery(document).on('click', '.remove-city', function(){
		jQuery(this).closest('.city-dropdown').remove();
	});

	jQuery(document).on('change', '.state-selectpicker', function(){
		var selected = parseInt(jQuery(this).find("option:selected").val());
		var wrapper = jQuery(jQuery(this).closest('.city-dropdown'));

		wrapper.find('.select-city-wrapper option').each(function(index, option) {
			jQuery(this).show();

		});

		if(selected) {
			wrapper.find('.select-city-wrapper option').each(function(index, option) {
				var rel = jQuery(this).attr('rel');
				if(rel && rel != selected) jQuery(this).hide();
			});
			wrapper.find('.city-selectpicker').selectpicker('refresh');
			wrapper.find('.select-city-wrapper').show();
		} else {
			wrapper.find('.city-selectpicker').selectpicker('refresh');
			wrapper.find('.select-city-wrapper').hide();
		}
	});

	jQuery('select[name="categories[]"]:last').change();
});

function showNotice(msg) {
	jQuery('#message .modal-header .modal-title').text("@lang('front/general.info')");
	jQuery('#message .modal-body').text(msg);

	jQuery('#message').modal('show');
}

function handleDrag(event) {
	updateLatLng(event.latLng.lat(), event.latLng.lng());
}

function updateLatLng(new_lat, new_lng) {
	jQuery('#lattitude').val(new_lat);
	jQuery('#longitude').val(new_lng);
}

function isInt(n) {
   return n % 1 === 0;
}

function canShowCity(last_category) {
	jQuery('.multicity').show();

	return true;
}

function addCity(last_category) {
	jQuery.showLoader();

	var selected = [];
	var main = [];
	jQuery('select[name^="multicategory"]').each(function() {
		selected.push(jQuery(this).val());
	});
	jQuery('select[name^="categories"]').each(function() {
		main.push(jQuery(this).val());
	});

	jQuery.ajax({
		url: '{!! URL::to('classified.category.multicity') !!}',
		type: 'post',
		data: {'selected': selected, 'selected_main': main, 'category': last_category, '_token': jQuery('input[name=_token]').val()},
		success: function(data) {
			if(data.success && !data.max) {
				showNotice("@lang('classified/form.multicity_exceed')");

				return true;
			} else if(data.success && data.cities) {
				var options = '<option value="0" selected>@lang('classified/form.select_city')</option>';
				var rel = '';
				var states = [];

				data.cities.sort(function(obj1, obj2) {
					if(obj1.title < obj2.title) return -1;
					if(obj1.title > obj2.title) return 1;
					return 0;
				});
				jQuery(data.cities).each(function(index, value) {
					if(value.state_id) {
						var id = states.length + 1;
						var found = states.some(function (el) {
							return el.state_id === value.state_id;
						});

						if (!found) {
							states.push({'state_id': value.state_id, 'state_title': value.state_title});
						}
						rel = 'rel="' + value.state_id +'"';
					} else rel = '';
					options += '<option value="' + value.id + '"' + rel + '>' + value.title + '</option>';
				});

				states.sort(function(obj1, obj2) {
					if(obj1.state_title < obj2.state_title) return -1;
					if(obj1.state_title > obj2.state_title) return 1;
					return 0;
				});

				var select = '';
				var display = '';
				if(states) {
					var state_options = '<option value="0" selected>@lang('general.select_option')</option>';
					jQuery(states).each(function(index, value) {
						state_options += '<option value="' + value.state_id + '">' + value.state_title + '</option>';
					})
					select += '<div class="state-select-wrapper"><select class="selectpicker state-selectpicker" name="states" ata-live-search="true">' + state_options + '</select></div>';
					display = 'style="display:none"';
				}

				select += '<div class="select-city-wrapper" ' + display + '><select class="selectpicker city-selectpicker" name="multicategory[]" data-live-search="true">' + options + '</select></div>';
				var dropdown = '<div class="col-md-12 city-dropdown"><div class="row"><div class="form-group"><div class="col-md-8">' + select + '</div><div class="col-md-4 text-right"><a href="javascript:void(0)" class="remove-city"><i class="fa fa-times-circle"></i> @lang('classified/form.remove_city')</a></div></div></div></div>';

				jQuery('.multicity-wrapper .dropdowns').append(dropdown);
				jQuery('.selectpicker').selectpicker();

                setTimeout(function() {
                    jQuery.hideLoader();
                }, 2000);

				return true;
			}

			showNotice("@lang('classified/form.try_again')");
		}
	}).done(function() {
		//jQuery.hideLoader();
	});
}
</script>
{!! TemplateHelper::includeDateScript() !!}
@stop
