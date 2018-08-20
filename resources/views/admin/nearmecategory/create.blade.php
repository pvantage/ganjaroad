@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('nearmecategory/title.create') :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">

    <!--end of page level css-->
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('nearmecategory/title.create')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i> Dashboard
            </a>
        </li>
        <li>@lang('nearmecategory/title.nearmecategories')</li>
        <li class="active">
            @lang('nearmecategory/title.create')
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
                        @lang('nearmecategory/title.create')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/nearmecategory/create'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('title', 'has-error') }}">
                        <label for="title" class="col-sm-2 control-label">
                            @lang('nearmecategory/form.name')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('nearmecategory/form.categoryname'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('title', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('position', 'has-error') }}">
						<label for="position" class="col-sm-2 control-label">
							@lang('nearmecategory/form.position')
						</label>
						<div class="col-sm-5">
							{!! Form::text('position', null, array('class' => 'form-control', 'placeholder'=>trans('nearmecategory/form.position_placeholder'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('position', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('icon', 'has-error') }}">
						<label class="col-sm-2 control-label">@lang('nearmecategory/form.icon')</label>
						<div class="fileinput fileinput-new col-sm-5" data-provides="fileinput">
							<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
								
							</div>
							<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
							<div>
								<span class="btn btn-primary btn-file">
									<span class="fileinput-new">@lang('front/general.select_image')</span>
									<span class="fileinput-exists">@lang('front/general.change')</span>
									<input type="file" name="icon" />
								</span>
								<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">@lang('front/general.remove')</span>
							</div>
						</div>
						
						<div class="col-sm-4">
							{!! $errors->first('icon', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('image', 'has-error') }}">
						<label class="col-sm-2 control-label">@lang('nearmecategory/form.lb-featured-img')</label>
						<div class="fileinput fileinput-new col-sm-5" data-provides="fileinput">
							<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
								
							</div>
							<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
							<div>
								<span class="btn btn-primary btn-file">
									<span class="fileinput-new">@lang('front/general.select_image')</span>
									<span class="fileinput-exists">@lang('front/general.change')</span>
									<input type="file" name="image" />
								</span>
								<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">@lang('front/general.remove')</span>
							</div>
						</div>
						
						<div class="col-sm-4">
							{!! $errors->first('image', '<span class="help-block">:message</span>') !!}
						</div>
					</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <a class="btn btn-danger" href="{{ URL::to('admin/nearmecategory/') }}">
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

<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>

@stop