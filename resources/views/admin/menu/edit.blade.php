@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('menu/title.edit')
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        @lang('menu/title.edit')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
         <li>@lang('menu/title.menu')</li>
        <li class="active">@lang('menu/title.edit')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('menu/title.edit')
                    </h4>
                </div>
				
				<div class="panel-body">
                    {!! Form::model($menu, array('url' => URL::to('admin/menu') . '/' . $menu->id.'/edit', 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('title', 'has-error') }}">
                        <label for="title" class="col-sm-2 control-label">
                            @lang('menu/form.title')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('menu/form.categoryname'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('title', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('url', 'has-error') }}">
                        <label for="url" class="col-sm-2 control-label">
                            @lang('menu/form.url')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('url', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('menu/form.url_placeholder'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('url', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('url', 'has-error') }}">
                        <label for="url" class="col-sm-2 control-label">
                            @lang('menu/form.published')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::select('published', array('0' => trans('general.no'), '1' => trans('general.yes')), null, array('class' => 'form-control select2')) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('published', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('url', 'has-error') }}">
                        <label for="url" class="col-sm-2 control-label">
                            @lang('menu/form.target')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::select('target', TemplateHelper::getMenuTargetArray(), null, array('class' => 'form-control select2')) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('target', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('position', 'has-error') }}">
                        <label for="position" class="col-sm-2 control-label">
                            @lang('menu/form.position')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('position', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('menu/form.url_placeholder'), $menu->id)) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('position', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('style', 'has-error') }}">
                        <label for="style" class="col-sm-2 control-label">
                            @lang('menu/form.style')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('style', null, array('class' => 'form-control input-lg', 'placeholder' => trans('menu/form.style'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('style', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<div class="form-group {{ $errors->first('parent', 'has-error') }}">
                        <label for="parent" class="col-sm-2 control-label">
                            @lang('menu/form.parent')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::SelectCategories('parent', $menus, null, array('class' => 'form-control select2', 'placeholder'=>trans('menu/form.select-category')), $menu->id) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('parent', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
                    <div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<a class="btn btn-danger" href="{{ URL::to('admin/menu') }}">
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

@stop