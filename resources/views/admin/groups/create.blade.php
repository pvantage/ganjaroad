@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    @lang('groups/title.create')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/groups/style.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/elements/checkbox.css') }}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        @lang('groups/title.create')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('groups/title.groups')</li>
        <li class="active">
            @lang('groups/title.create')
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('groups/title.create')
                    </h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="post" action="">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="form-group {{ $errors->
                            first('name', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                @lang('groups/form.name')
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Group Name"
                                       value="{!! old('name') !!}">
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('name', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>
						
						<div class="form-group">
							<label for="slug" class="col-sm-2 control-label">@lang('groups/form.slug')</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="slug" value="{!! old('slug') !!}" />
							</div>
							<div class="col-sm-4">
								{!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
							</div>
						</div>
						
						<div class="form-group checkbox-group">
							<label for="slug" class="col-sm-2 control-label">@lang('groups/form.permissions')</label>
							<div class="col-sm-5">
								{!! Form::Permissions() !!}
							</div>
						</div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('groups') }}">
                                    @lang('button.cancel')
                                </a>
                                <button type="submit" class="btn btn-success">
                                    @lang('button.save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop
