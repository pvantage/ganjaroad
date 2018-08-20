@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('blog/title.edit')
@parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        @lang('blog/title.edit')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('blog/title.blog')</li>
        <li class="active">@lang('blog/title.edit')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('blogcategory/title.edit')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::model($blogcategory, array('url' => URL::to('admin/blogcategory') . '/' . $blogcategory->id.'/edit', 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('title', 'has-error') }}">
						<label for="title" class="col-sm-2 control-label">
							@lang('blogcategory/form.name')
						</label>
						<div class="col-sm-5">
							{!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('blogcategory/form.categoryname'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('title', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('slug', 'has-error') }}">
						<label for="slug" class="col-sm-2 control-label">
							@lang('blogcategory/form.slug')
						</label>
						<div class="col-sm-5">
							{!! Form::text('slug', null, array('class' => 'form-control', 'placeholder'=>trans('blogcategory/form.slug'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->first('meta_description', 'has-error') }}">
						<label for="meta_description" class="col-sm-2 control-label">
							@lang('blogcategory/form.meta-desc')
						</label>
						<div class="col-sm-5">
							{!! Form::textarea('meta_description', null, array('class' => 'form-control input-lg', '', 'placeholder'=> trans('blogcategory/form.meta-desc'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('meta_description', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
                        
					<div class="form-group {{ $errors->first('meta_keywords', 'has-error') }}">
						<label for="meta_keywords" class="col-sm-2 control-label">
							@lang('blogcategory/form.meta-keywords')
						</label>
						<div class="col-sm-5">
							{!! Form::textarea('meta_keywords', null, array('class' => 'form-control input-lg', '', 'placeholder'=> trans('blogcategory/form.meta-keywords'))) !!}
						</div>
						<div class="col-sm-4">
							{!! $errors->first('meta_keywords', '<span class="help-block">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-4">
							<a class="btn btn-danger" href="{{ URL::to('admin/blogcategory') }}">
								@lang('button.cancel')
							</a>
							<button type="submit" class="btn btn-success">
								@lang('button.update')
							</button>
						</div>
					</div>
					{!! Form::hidden('id', $blogcategory->id) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>

@stop