@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('nearme/title.add-nearme') :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/map.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
	<link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/images-upload.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/nearme.css') }}">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('nearme/title.add-nearme')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('nearme/title.nearme')</a>
        </li>
        <li class="active">@lang('nearme/title.add-nearme')</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
            <!-- errors -->
           <div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
            {!! Form::open(array('url' => URL::to('admin/nearme/create'), 'method' => 'post', 'id' => 'nearme', 'class' => 'bf', 'files'=> true)) !!}
                 <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.title'))) !!}
                        </div>
                        <div class='box-body pad'>
                            {!! TemplateHelper::renderWyswingEditor() !!}
                        </div>
						
						<div class="address-wrapper">
							<div class="form-group">
								{!! Form::label('address1', trans('nearme/form.address')) !!}
								{!! Form::text('address1', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.address1'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::text('address2', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address2'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('country', trans('nearme/form.country')) !!}
								{!! Form::select('country', TemplateHelper::getCountriesList(false), 'US', array('class' => 'form-control select2', 'id' => 'country', 'placeholder'=>trans('nearme/form.country'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('state', trans('nearme/form.select-state')) !!}
								{!! Form::SelectState('state', $nearmecategory, null, array('class' => 'form-control select2', 'required' => 'required', 'placeholder'=>trans('nearme/form.select-state'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('city', trans('nearme/form.city')) !!}
								{!! Form::text('city', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.city'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('zip', trans('nearme/form.zip')) !!}
								{!! Form::text('zip', null, array('class' => 'form-control input-lg map-input', 'required' => 'required', 'placeholder'=> trans('nearme/form.zip'))) !!}
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
						
						<div class="row form-inline">
							
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('lattitude', trans('nearme/form.lattitude')) !!}
									{!! Form::text('lattitude', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.lattitude'))) !!}
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('longitude', trans('nearme/form.longitude')) !!}
									{!! Form::text('longitude', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.lattitude'))) !!}
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
                            {!! Form::label('hours', trans('nearme/form.hours')) !!}
							{!! Form::Hours('hours', null) !!}
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('facebook', trans('nearme/form.facebook')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('facebook', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.facebook'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('instagram', trans('nearme/form.instagram')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('instagram', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.instagram'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('twitter', trans('nearme/form.twitter')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('twitter', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.twitter'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('first_time', trans('nearme/form.first_time')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::textarea('first_time', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.first_time'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<label for="nearmeitems">{!!  trans('nearme/form.menu') !!}</label>
							</div>
							<div class="col-md-12">
								{!! Form::NearmeItems('nearmeitems', null) !!}
							</div>
						</div>
						
						<div class="col-md-12">
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
						
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
						
						<div class="form-group">
							{!! Form::label('user_id', trans('nearme/form.user')) !!}
							{!! Form::select('user_id', $users, $current_user, array('class' => 'form-control select2 select')) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('published', trans('nearme/form.published')) !!}
							{!! Form::YesNo('published') !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('active', trans('nearme/form.active')) !!}
							{!! Form::YesNo('active') !!}
                        </div>
						
						<div class="form-group">
							@lang('nearme/form.active_comment')
						</div>
						
						<div class="form-group">
                            {!! Form::label('paid', trans('nearme/form.paid')) !!}
							{!! Form::YesNo('paid') !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('approved', trans('nearme/form.approved')) !!}
							{!! Form::YesNo('approved') !!}
                        </div>
						
						<div class="form-group">
							{!! Form::label('active_to', trans('nearme/form.active_to')) !!}
							{!! Form::DateTimeInput('active_to')!!}
						</div>
						
						<div class="form-group">
							@lang('nearme/form.approved_comment')
						</div>
							
                        <div class="form-group">
                            {!! Form::label('category_id', trans('nearme/form.category')) !!}
                            {!! Form::select('category_id',$nearmecategory ,null, array('class' => 'form-control select2', 'required' => 'required', 'placeholder'=>trans('nearme/form.select-category'))) !!}
                        </div>
						
						<div class="form-group">
							{!! Form::label('email', trans('nearme/form.email')) !!}
							{!! Form::text('email', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.email'))) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('phone', trans('nearme/form.phone')) !!}
							{!! Form::text('phone', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.phone'))) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('url', trans('nearme/form.url')) !!}
							{!! Form::text('url', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.url'))) !!}
						</div>
						
						<div class="form-group">
                            {!! Form::label('delivery', trans('nearme/form.delivery')) !!}
							{!! Form::YesNo('delivery', null) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('atm', trans('nearme/form.atm')) !!}
							{!! Form::YesNo('atm', null) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('min_age', trans('nearme/form.min_age')) !!}
                            {!! Form::select('min_age', TemplateHelper::getMinAgeSelect(), null, array('class' => 'form-control select2', 'placeholder'=>trans('nearme/form.min_age'))) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('wheelchair', trans('nearme/form.wheelchair')) !!}
							{!! Form::YesNo('wheelchair', null) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('security', trans('nearme/form.security')) !!}
							{!! Form::YesNo('security', null) !!}
                        </div>

						<div class="form-group">
                            {!! Form::label('credit_cards', trans('nearme/form.credit_cards')) !!}
							{!! Form::YesNo('credit_cards', null) !!}
                        </div>

						<div class="form-group">
							<div class="col-md-12">

							<label>@lang('nearme/form.image')</label>
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

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('nearme/form.publish')</button>
                            <a href="{!! URL::to('admin/nearme') !!}"
                               class="btn btn-danger">@lang('nearme/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                {!! Form::close() !!}
        </div>
    </div>
    <!--main content ends-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit nearme-->
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>

<script type="text/javascript">
var prefix = 1;
var is_upload = false;
jQuery( document ).ready(function() {
	
	jQuery("input[type='checkbox']").iCheck({
		checkboxClass: 'icheckbox_minimal-blue'
	});
	
	jQuery('#other_address').on('ifChanged', function(event){
		if(jQuery(this).is(":checked")) {
			jQuery('#full_address_wrapper').fadeIn();
		} else {
			jQuery('#full_address_wrapper').fadeOut();
		}
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
	
	var table_row = jQuery('{!! Form::NearmeBlankItem() !!}');
	jQuery('#addtablerow').on('click', function() {
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
		
		jQuery('#nearmeitems tbody').append(table_clone[0].outerHTML);
		prefix = prefix + 1;
	});
	
	jQuery(document).on('click', '.remove_row', function() {
		jQuery(this).closest('tr').fadeOut('slow', function(){
			jQuery(this).closest('tr').remove();
		});
	});
	
	jQuery('form#nearme').submit(function( event ) {
        if (is_upload) {
            event.preventDefault();
            event.stopPropagation();
		}
	   
        return;
    });
	
	jQuery('.yesno-value').each(function() {
		jQuery(this).bootstrapSwitch('state');
	});
	
	jQuery('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = jQuery(this).attr('data-config');

		jQuery('input[name="'+id+'"]').val(+state);
	});
	
	var lat = {!! TemplateHelper::getDefaultLattitude() !!};;
	var lng = {!! TemplateHelper::getDefaultLongitude() !!};;
	
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
		var address = [];

		var address_line = jQuery('[name=address1]').val() + ' ' + jQuery('[name=address2]').val();
		if(address_line && address_line != ' ') address.push(address_line);
		
		var country = jQuery('[name=country]').val();
		if(country && country != '') address.push(country);
		
		var city = jQuery('[name=city]').val();
		if(city && city != '') address.push(city);
		
		var zip = jQuery('[name=zip]').val();
		if(zip && zip != '') address.push(zip);
		
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
	});
	
	jQuery("#country").select2({
		placeholder: "@lang('nearme/form.select_country')",
		theme: 'bootstrap',
		allowClear: true,
		formatResult: format,
		formatSelection: format,
		templateResult: format,
		escapeMarkup: function (m) {
			return m;
		},
	});
	jQuery("#country").trigger('change');
	
	jQuery(".select2.select").select2({
		placeholder: jQuery(this).attr('placeholder'),
		theme: 'bootstrap',
		allowClear: true,
	});
});

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
{!! TemplateHelper::includeDateScript() !!}
@stop
