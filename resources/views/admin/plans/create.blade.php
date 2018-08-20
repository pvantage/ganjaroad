@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('plans/title.add-plan') :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css') }}" rel="stylesheet" type="text/css" media="all" />
	
	<link href="{{ asset('assets/css/pages/categorytree.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/vendors/jtree/css/style.css') }}" rel="stylesheet" type="text/css" />

    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('plans/title.add-plan')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('plans/title.plan')</a>
        </li>
        <li class="active">@lang('plans/title.add-plan')</li>
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
            {!! Form::open(array('url' => URL::to('admin/plans/create'), 'method' => 'post', 'id' => 'plans_form', 'class' => 'bf', 'files'=> true)) !!}
				<div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('plans/form.title'))) !!}
                        </div>
                        <div class='box-body pad'>
                            {!! Form::textarea('description', null, array('class' => 'textarea form-control', 'rows'=>'5', 'placeholder'=>trans('plans/form.description'), 'style'=>'style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"')) !!}
                        </div>
						
						<?php /*
						<div class="form-group">
                            {!! Form::label('classifieds_categories', trans('plans/form.classifieds_categories')) !!}
							<div id="classifieds_categories"></div>
                        </div>
						*/ ?>
						
						<div class="form-group">
                            {!! Form::label('nearme_categories', trans('plans/form.nearme_categories')) !!}
							<div id="nearme_categories"></div>
                        </div>
						
						<div class="form-group">
                            {!! Form::label('ads_positions', trans('plans/form.ads_positions')) !!}
							<div id="ads_positions"></div>
                        </div>
						
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
                        <div class="form-group">
							<div class="form-group">
								{!! Form::label('published', trans('plans/form.published')) !!}
								{!! Form::select('published', array('0' => trans('general.no'), '1' => trans('general.yes')), null, array('class' => 'form-control select2')) !!}
							</div>
						
                            <div class="form-group hidden">
								{!! Form::label('slug', trans('plans/form.code')) !!}
                                {!! Form::text('slug', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('plans/form.code'))) !!}
                            </div>
							
							<div class="form-group">
								{!! Form::label('amount', trans('plans/form.amount')) !!}
								{!! Form::text('amount', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('plans/form.amount_placeholder'))) !!}
							</div>
							
							<div class="form-group">
								{!! Form::label('priority', trans('plans/form.priority')) !!}
                                {!! Form::text('priority', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('plans/form.priority'))) !!}
                            </div>
							
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('plans/form.publish')</button>
                            <a href="{!! URL::to('admin/plans') !!}"
                               class="btn btn-danger">@lang('plans/form.discard')</a>
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
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/jtree/js/jstree.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js') }}" ></script>
<script type="text/javascript">
$("document").ready(function() {
	$("input[name='amount']").TouchSpin({
		min: 0,
		max: 10000,
		step: 0.1,
		decimals: 2,
		boostat: 5,
		maxboostedstep: 10,
		prefix: '{!! TemplateHelper::getCurrencySymbol() !!}'
	});
	
	$('#classifieds_categories').jstree({
		'core' : {
			'animation' : 0,
			'check_callback': true,
			'themes' : { 'default' : true },
			'data' : function (obj, callback) {
				$.ajax({
					type: 'GET', 
					url: '{!! route('plans.classifiedcategories.data') !!}', 
					data: {id: obj.id}, 
					dataType: 'json',
					success: function (data) {
						callback.call(this, data.data);
					}
				});
			}
		},
		'checkbox' : {
			'keep_selected_style' : true,
			'three_state' : false,
		},
		'types' : {
			'#' : {
				'icon' : 'glyphicon glyphicon-file',
			},
			'root' : {
				"icon" : 'glyphicon glyphicon-file',
			},
			'default' : {
				'icon' : 'glyphicon glyphicon-file',
			},
			"notpublished" : {
				"icon" : "glyphicon glyphicon-file notpublished",
			},
		},
		'plugins' : [
			'types', 'checkbox'
		]
	});
	
	$('#nearme_categories').jstree({
		'core' : {
			'animation' : 0,
			'check_callback': true,
			'themes' : { 'default' : true },
			'data' : function (obj, callback) {
				$.ajax({
					type: 'GET', 
					url: '{!! route('plans.nearmecategories.data') !!}', 
					data: {id: obj.id}, 
					dataType: 'json',
					success: function (data) {
						callback.call(this, data.data);
					}
				});
			}
		},
		'checkbox' : {
			'keep_selected_style' : true,
			'three_state' : false,
		},
		'types' : {
			'#' : {
				'icon' : 'glyphicon glyphicon-file',
			},
			'root' : {
				"icon" : 'glyphicon glyphicon-file',
			},
			'default' : {
				'icon' : 'glyphicon glyphicon-file',
			},
			"notpublished" : {
				"icon" : "glyphicon glyphicon-file notpublished",
			},
		},
		'plugins' : [
			'types', 'checkbox'
		]
	});
	
	$('#ads_positions').jstree({
		'core' : {
			'animation' : 0,
			'check_callback': true,
			'themes' : { 'default' : true },
			'data' : function (obj, callback) {
				$.ajax({
					type: 'GET', 
					url: '{!! route('plans.adspositions.data') !!}', 
					data: {id: obj.id}, 
					dataType: 'json',
					success: function (data) {
						callback.call(this, data.data);
					}
				});
			}
		},
		'checkbox' : {
			'keep_selected_style' : true,
			'three_state' : false,
		},
		'types' : {
			'#' : {
				'icon' : 'glyphicon glyphicon-file',
			},
			'root' : {
				"icon" : 'glyphicon glyphicon-file',
			},
			'default' : {
				'icon' : 'glyphicon glyphicon-file',
			},
			"notpublished" : {
				"icon" : "glyphicon glyphicon-file notpublished",
			},
		},
		'plugins' : [
			'types', 'checkbox'
		]
	});
	
	$(document).on('submit','#plans_form', function(e){
		var selectedElmsIds = [];
		var selectedElms = $('#classifieds_categories').jstree('get_checked', true);
		$.each(selectedElms, function() {
			$('<input>').attr({
				type: 'hidden',
				name: 'classifieds_categories[]',
				value: this.id
			}).appendTo('#plans_form');
		});
		
		var selectedElmsIds = [];
		var selectedElms = $('#nearme_categories').jstree('get_checked', true);
		$.each(selectedElms, function() {
			$('<input>').attr({
				type: 'hidden',
				name: 'nearme_categories[]',
				value: this.id
			}).appendTo('#plans_form');
		});
		
		var selectedElmsIds = [];
		var selectedElms = $('#ads_positions').jstree('get_checked', true);
		$.each(selectedElms, function() {
			$('<input>').attr({
				type: 'hidden',
				name: 'ads_positions[]',
				value: this.id
			}).appendTo('#plans_form');
		});

		return true;
	});
});
</script>
@stop