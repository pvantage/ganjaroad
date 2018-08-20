@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('nearme/title.edit')
@parent
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
    <h1>@lang('nearme/title.edit')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('nearme/title.nearme')</a>
        </li>
        <li class="active">@lang('nearme/title.edit')</li>
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
           {!! Form::model($nearme, array('url' => URL::to('admin/nearme/' . $nearme->id.'/edit'), 'id' => 'nearme', 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('nearme/form.title'))) !!}
                        </div>
                        <div class='box-body pad'>
                            {!! TemplateHelper::renderWyswingEditor($nearme->content) !!}
                        </div>

						<div class="address-wrapper">
							<div class="form-group">
								{!! Form::label('address1', trans('nearme/form.address')) !!}
								{!! Form::text('address1', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address1'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::text('address2', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.address2'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('country', trans('nearme/form.country')) !!}
								{!! Form::select('country', TemplateHelper::getCountriesList(false), null, array('class' => 'form-control select2', 'id' => 'country', 'placeholder'=>trans('nearme/form.country'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('state', trans('nearme/form.select-state')) !!}
								{!! Form::SelectState('state', $nearmecategory, null, array('class' => 'form-control select2', 'placeholder'=>trans('nearme/form.select-state'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('city', trans('nearme/form.city')) !!}
								{!! Form::text('city', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.city'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('zip', trans('nearme/form.zip')) !!}
								{!! Form::text('zip', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.zip'))) !!}
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
							{!! Form::Hours('hours', $nearme->hours) !!}
                        </div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('facebook', trans('nearme/form.facebook')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('facebook', $nearme->facebook, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.facebook'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('instagram', trans('nearme/form.instagram')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('instagram', $nearme->instagram, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.instagram'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('twitter', trans('nearme/form.twitter')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::text('twitter', $nearme->twitter, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.twitter'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-3">
									{!! Form::label('first_time', trans('nearme/form.first_time')) !!}
								</div>
								<div class="col-md-6">
									{!! Form::textarea('first_time', $nearme->first_time, array('class' => 'form-control input-lg', 'placeholder'=> trans('nearme/form.first_time'))) !!}
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-12">
								<label for="nearmeitems">{!!  trans('nearme/form.menu') !!}</label>
							</div>
							<div class="col-md-12">
								@if($nearme->items()->count())
									{!! Form::NearmeItems('nearmeitems', $nearme) !!}
								@else
									{!! Form::NearmeItems('nearmeitems', null) !!}
								@endif
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
							<button class="btn btn-primary" value="btn-primary" type="button" name="color">@lang('nearme/form.views'): {!! $nearme->views !!}</button>
						</div>
						
						<div class="form-group">
							{!! $nearme->getAdminAgreementTxt() !!}
						</div>
						
						@if($nearme->canBeRefunded())
							<div class="form-group">
								<a href="#" data-toggle="modal"
									data-target="#refund" title="@lang('nearme/form.give_refund')" class="btn btn-danger">
									@lang('nearme/form.give_refund')
								</a>
							</div>
						@endif
						
						<div class="form-group">
							{!! Form::label('user_id', trans('nearme/form.user')) !!}
							{!! Form::select('user_id', $users, $nearme->user_id, array('class' => 'form-control select2 select')) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('published', trans('nearme/form.published')) !!}
							{!! Form::YesNo('published', $nearme->published) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('last_updated', trans('nearme/form.updated')) !!}:&nbsp;
							{!! Form::label('last_updated_value', TemplateHelper::formatDate($nearme->updated_at)) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('active', trans('nearme/form.active')) !!}
							{!! Form::YesNo('active', $nearme->active) !!}
                        </div>
						
						<div class="form-group">
							@lang('nearme/form.active_comment')
						</div>
						
						<div class="form-group">
                            {!! Form::label('paid', trans('nearme/form.paid')) !!}
							{!! Form::YesNo('paid', $nearme->paid) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('approved', trans('nearme/form.approved')) !!}
							{!! Form::YesNo('approved', $nearme->approved) !!}
                        </div>
						
						<div class="form-group">
							{!! Form::label('active_to', trans('classified/form.active_to')) !!}
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
							{!! Form::text('url', null, array('class' => 'form-control input-lg map-input', 'placeholder'=> trans('nearme/form.url'))) !!}
						</div>
						
						<div class="form-group">
                            {!! Form::label('delivery', trans('nearme/form.delivery')) !!}
							{!! Form::YesNo('delivery', null) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('atm', trans('nearme/form.atm')) !!}
							{!! Form::YesNo('atm', $nearme->atm) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('min_age', trans('nearme/form.min_age')) !!}
                            {!! Form::select('min_age', TemplateHelper::getMinAgeSelect() ,$nearme->min_age, array('class' => 'form-control select2', 'placeholder'=>trans('nearme/form.min_age'))) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('wheelchair', trans('nearme/form.wheelchair')) !!}
							{!! Form::YesNo('wheelchair', $nearme->wheelchair) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('security', trans('nearme/form.security')) !!}
							{!! Form::YesNo('security', $nearme->security) !!}
                        </div>

						<div class="form-group">
                            {!! Form::label('credit_cards', trans('nearme/form.credit_cards')) !!}
							{!! Form::YesNo('credit_cards', $nearme->credit_cards) !!}
                        </div>
						
						<?php /*
						<div class="form-group">
							{!! Form::label('recurring', trans('nearme/form.recurring')) !!}
							{!! Form::YesNo('recurring', $nearme->recurring) !!}
                        </div>
						*/ ?>
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<label>@lang('nearme/form.image')</label>
								</div>
								<div class="col-md-12">
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
						
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('nearme/form.update')</button>
                            <a href="{!! URL::to('admin/nearme') !!}"
                               class="btn btn-danger">@lang('nearme/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                <!-- /.row -->
				
				@forelse($nearme->images as $image)
					<input type="hidden" name="images[]" value="{{ $image->image }}" />
				@empty
				@endforelse
				
			{!! Form::close() !!}
			
			@include('admin/layouts/item_transaction_history', ['item' => $nearme])
        </div>
    </div>
    <!--main content ends-->
</section>

@if($nearme->canBeRefunded())
	<div class="modal fade" id="refund" tabindex="-2" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="refund_label">@lang('nearme/form.give_refund')</h4>
				</div>
				<div class="modal-body">
					{!! Form::open(array('url' => route('refund', ['type' => 'nearme', 'item_id' => $nearme->id, 'transaction_id' => $last_transaction->payment->transaction_id]), 'method' => 'post', 'class' => 'bf')) !!}
						<div class="form-group">
							{!! Form::text('amount', TemplateHelper::getItemMaxRefund($last_transaction->payment->transaction_id, $nearme), array('id' => 'refund_amount', 'placeholder'=> trans('nearme/form.amount_to_refund'))) !!}
						</div>
						<button type="submit" class="btn btn-success">@lang('nearme/form.refund')</button>
						<a href="#" class="btn btn-danger" data-dismiss="modal">@lang('nearme/form.discard')</a>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endif

@stop
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit nearme-->
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js') }}" ></script>
<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/dropzone/js/dropzone.js') }}" ></script>

<script type="text/javascript">
var is_upload = false;
var prefix = 1;
jQuery( document ).ready(function() {
	
	@if($nearme->canBeRefunded())
		jQuery("#refund_amount").TouchSpin({
			min: 0,
			max: {{ TemplateHelper::getItemMaxRefund($last_transaction->payment->transaction_id, $nearme) }},
			step: 0.1,
			decimals: 2,
			boostat: 5,
			maxboostedstep: 10,
			prefix: '{!! TemplateHelper::getCurrencySymbol() !!}'
		});
	@endif
	
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
	
	prefix = jQuery('#nearmeitems tbody tr').length;
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
					this.options.thumbnail.call(this, mockFile, "{{ URL::to('uploads/nearme') }}/{{ $nearme->id }}/{{ $image->image }}");
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
	
	var lat = {!! $nearme->lattitude !!};
	var lng = {!! $nearme->longitude !!};
	
	var map = new GMaps({
		el: '#map',
		lat: lat,
		lng: lng,
		zoom: 12
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