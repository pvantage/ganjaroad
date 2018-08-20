@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('nearmeitemscategory/title.edit')
@parent
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
        @lang('nearmeitemscategory/title.edit')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('nearmeitemscategory/title.nearmeitemscategory')</li>
        <li class="active">@lang('nearmeitemscategory/title.edit')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('nearmeitemscategory/title.edit')
                    </h4>
                </div>
                <div class="panel-body">
					{!! Form::model($nearmeitemscategory, array('url' => URL::to('admin/nearmeitemscategory/' . $nearmeitemscategory->id.'/edit'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> false)) !!}
                   
				   <div class="form-group {{ $errors->first('title', 'has-error') }}">
                        <label for="title" class="col-sm-2 control-label">
                            @lang('nearmeitemscategory/form.name')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('nearmeitemscategory/form.categoryname'))) !!}
                        </div>
                        <div class="col-sm-4">
                            
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('published', 'has-error') }}">
						<label for="published" class="col-sm-2 control-label">
							@lang('nearmeitemscategory/form.published')
						</label>
						<div class="col-sm-5" style="display: inline-table">
							{!! Form::YesNo('published') !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('published', '<span class="help-block">:message</span> ') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('position', 'has-error') }}">
						<label for="position" class="col-sm-2 control-label">
							@lang('nearmeitemscategory/form.position')
						</label>
						<div class="col-sm-5">
							{!! Form::text('position', null, array('class' => 'form-control', 'placeholder'=>trans('nearmeitemscategory/form.position_placeholder'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('position', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<a class="btn btn-danger" href="{{ URL::to('admin/nearmeitemscategory') }}">
								@lang('button.cancel')
							</a>
							<button type="submit" class="btn btn-success">
								@lang('button.update')
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
<!-- begining of page level js -->
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>

<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery('.yesno-value').each(function() {
		jQuery(this).bootstrapSwitch('state');
	});
	
	jQuery('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = jQuery(this).attr('data-config');

		jQuery('input[name="'+id+'"]').val(+state);
	});
});
</script>
@stop