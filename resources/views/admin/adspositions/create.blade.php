@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('adspositions/title.create') :: @parent
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('adspositions/title.create')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i> Dashboard
            </a>
        </li>
        <li>@lang('adspositions/title.nearmecategories')</li>
        <li class="active">
            @lang('adspositions/title.create')
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
                        @lang('adspositions/title.create')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/adspositions/create'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('title', 'has-error') }}">
                        <label for="title" class="col-sm-2 control-label">
                            @lang('adspositions/form.name')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('adspositions/form.positionname'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('title', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('slug', 'has-error') }}">
						<label for="slug" class="col-sm-2 control-label">
							@lang('adspositions/form.slug')
						</label>
						<div class="col-sm-5">
							{!! Form::text('slug', null, array('class' => 'form-control', 'placeholder'=>trans('adspositions/form.slug'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('width', 'has-error') }}">
						<label for="width" class="col-sm-2 control-label">
							@lang('adspositions/form.width')
						</label>
						<div class="col-sm-5">
							{!! Form::text('width', null, array('class' => 'form-control', 'placeholder'=>trans('adspositions/form.width'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('width', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('height', 'has-error') }}">
						<label for="height" class="col-sm-2 control-label">
							@lang('adspositions/form.height')
						</label>
						<div class="col-sm-5">
							{!! Form::text('height', null, array('class' => 'form-control', 'placeholder'=>trans('adspositions/form.height'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('height', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('title', 'has-error') }}">
						<label for="published" class="col-sm-2 control-label">
							@lang('adspositions/form.published')
						</label>
						<div class="col-sm-5">
							{!! Form::select('published', array('0' => trans('general.no'), '1' => trans('general.yes')), null, array('class' => 'form-control select2')) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('published', '<span class="help-block">:message</span>') !!}
						</div>
					</div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <a class="btn btn-danger" href="{{ URL::to('admin/adspositions/') }}">
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
