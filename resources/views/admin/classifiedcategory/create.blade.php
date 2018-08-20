@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('classifiedcategory/title.create') :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.css') }}" media="all" />
    <link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('classifiedcategory/title.create')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i> Dashboard
            </a>
        </li>
        <li>@lang('classifiedcategory/title.classifiedcategories')</li>
        <li class="active">
            @lang('classifiedcategory/title.create')
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('classifiedcategory/title.create')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/classifiedcategory/create'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('title', 'has-error') }}">
                        <label for="title" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.title')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('classifiedcategory/form.categoryname'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('title', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('published', 'has-error') }}">
                        <label for="published" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.published')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::select('published', array('0' => trans('general.no'), '1' => trans('general.yes')), null, array('class' => 'form-control select2')) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('published', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('amount', 'has-error') }}">
                        <label for="amount" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.amount')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('amount', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('classifiedcategory/form.amount_placeholder'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('amount', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('parent', 'has-error') }}">
                        <label for="parent" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.parent')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::SelectCategories('parent', $classifiedscategories, null, array('class' => 'form-control select2 selectpicker', 'data-live-search' => 'true', 'placeholder'=>trans('classifiedcategory/form.select-category')), null) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('parent', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('position', 'has-error') }}">
                        <label for="position" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.position')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('position', null, array('class' => 'form-control', 'placeholder'=>trans('classifiedcategory/form.position'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('position', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<?php /*
					<div class="form-group {{ $errors->first('country', 'has-error') }}">
                        <label for="country" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.country')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('country', null, array('class' => 'form-control', 'placeholder'=>trans('classifiedcategory/form.country'))) !!}
							<div class="note">
								@lang('classifiedcategory/form.country_note')
							</div>
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('country', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('state', 'has-error') }}">
                        <label for="state" class="col-sm-2 control-label">
                            @lang('classifiedcategory/form.state')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('state', null, array('class' => 'form-control', 'placeholder'=>trans('classifiedcategory/form.state'))) !!}
							<div class="note">
								@lang('classifiedcategory/form.state_note')
							</div>
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('state', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					*/ ?>
					
					<div class="form-group home-use {{ $errors->first('home', 'has-error') }}">
                        <label for="home" class="col-sm-2 control-label">
                            {!! Form::label('home', trans('classifiedcategory/form.home')) !!}
                        </label>
                        <div class="col-sm-5">
                            {!! Form::YesNo('home', null) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('home', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('address', 'has-error') }}" style="display: none">
                        <label for="address" class="col-sm-2 control-label">
                            {!! Form::label('address', trans('classifiedcategory/form.address')) !!}
                        </label>
                        <div class="col-sm-5">
							{!! Form::text('address', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('classifiedcategory/form.address'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('address', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group hide {{ $errors->first('lattitude', 'has-error') }}">
                        <label for="lattitude" class="col-sm-2 control-label">
                            {!! Form::label('lattitude', trans('classifiedcategory/form.lattitude')) !!}
                        </label>
                        <div class="col-sm-5">
							{!! Form::text('lattitude', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('classifiedcategory/form.lattitude'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('lattitude', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group hide {{ $errors->first('longitude', 'has-error') }}">
                        <label for="longitude" class="col-sm-2 control-label">
                            {!! Form::label('longitude', trans('classifiedcategory/form.longitude')) !!}
                        </label>
                        <div class="col-sm-5">
							{!! Form::text('longitude', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('classifiedcategory/form.longitude'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('longitude', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('use_schema', 'has-error') }}">
                        <label for="use_schema" class="col-sm-2 control-label">
                            {!! Form::label('use_schema', trans('classifiedcategory/form.use_schema')) !!}
                        </label>
                        <div class="col-sm-5">
                            {!! Form::YesNo('use_schema', null) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('use_schema', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <a class="btn btn-danger" href="{{ URL::to('admin/classifiedcategory/') }}">
                                @lang('button.cancel')
                            </a>
                            <button type="submit" class="btn btn-success">
                                @lang('button.save')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js') }}" ></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-select/bootstrap-select.js') }}"></script>

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
	
	$('.yesno-value').each(function() {
		$(this).bootstrapSwitch('state');
	});
	
	$('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = $(this).attr('data-config');
		if($(this).closest('.home-use').length){
			if(state) {
				$('#address').closest('.form-group').show();
			} else $('#address').closest('.form-group').hide();
		}
		$('input[name="'+id+'"]').val(+state);		
	});
});
</script>
@stop