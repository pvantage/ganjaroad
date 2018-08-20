@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('classifiedschema/title.recreate') :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('classifiedschema/title.recreate')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('classifiedschema/title.classifiedschema')</li>
        <li class="active">@lang('classifiedschema/title.recreate')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('classifiedschema/title.recreate')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/classifiedschema/recreate'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> false)) !!}
                    <div class="form-group {{ $errors->first('category_id', 'has-error') }}">
                        <label for="category_id" class="col-sm-4 control-label">
                            @lang('classifiedschema/form.recreate_select')
                        </label>
                        <div class="col-sm-4">
                            {!! Form::Select('category_id', $classifiedscategories, null, array('class' => 'form-control select2', 'placeholder'=>trans('classifiedschema/form.all'), 'autocomplete' => 'off'), 0) !!}
						</div>
                        <div class="col-sm-4">
                            {!! $errors->first('category_id', '<span class="help-block">:message</span> ') !!}
                        </div>
					</div>
					
					<div class="form-group {{ $errors->first('remove', 'has-error') }}">
						<label for="remove" class="col-sm-4 control-label">
							@lang('classifiedschema/form.recreate_remove')
						</label>
						<div class="col-sm-4">
							{!! Form::YesNo('remove', null) !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('price', 'has-error') }}">
						<label for="price" class="col-sm-4 control-label">
							@lang('classifiedschema/form.recreate_price')
						</label>
						<div class="col-sm-4">
							{!! Form::YesNo('price', null) !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('price', 'has-error') }}">
						<label for="price" class="col-sm-4 control-label">
							@lang('classifiedschema/form.recreate_tier')
						</label>
						<div class="col-sm-4">
							{!! Form::YesNo('tier', null) !!}
						</div>
					</div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-4">
                            <button type="submit" class="btn btn-success">
                                @lang('button.recreate')
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
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>

<script type="text/javascript">
$("document").ready(function() {
	$('.yesno-value').each(function() {
		$(this).bootstrapSwitch('state');
	});
	
	$('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = $(this).attr('data-config');

		$('input[name="'+id+'"]').val(+state);
	});
});
</script>
@stop