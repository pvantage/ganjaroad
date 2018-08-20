@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('users/title.export') :: @parent
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>@lang('users/title.users')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('users/title.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('users/title.users')</a></li>
        <li class="active">@lang('users/title.export')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('users/title.export')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/users/export'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('category_id', 'has-error') }}">
                        <label for="category_id" class="col-sm-4 control-label">
                            @lang('users/form.do_export')
                        </label>
					</div>
					
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-4">
                            <button type="submit" class="btn btn-success">
                                @lang('button.export')
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
