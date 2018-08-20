@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('nearme/title.import') :: @parent
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('nearme/title.import')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('nearme/title.settings')</li>
        <li class="active">@lang('nearme/title.import')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('nearme/title.import')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/settings/import'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('category_id', 'has-error') }}">
                        <label for="category_id" class="col-sm-4 control-label">
                            @lang('nearme/form.import_select')
                        </label>
                        <div class="col-sm-4">
                            {!! Form::Select('category_id', $nearmecategories, null, array('class' => 'form-control select2', 'placeholder'=>trans('nearme/form.category'), 'autocomplete' => 'off'), 0) !!}
						</div>
                        <div class="col-sm-4">
                            {!! $errors->first('category_id', '<span class="help-block">:message</span> ') !!}
                        </div>
					</div>
					
					<div class="form-group {{ $errors->first('file', 'has-error') }}">
						<label for="file" class="col-sm-4 control-label">
							@lang('nearme/form.import_file')
						</label>
						<div class="col-sm-4">
							{!! Form::file('file', null, array('class' => 'form-control')) !!}
						</div>
						<div class="col-sm-4">
                            {!! $errors->first('file', '<span class="help-block">:message</span> ') !!}
                        </div>
					</div>

                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-4">
                            <button type="submit" class="btn btn-success">
                                @lang('button.import')
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
