@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('settings/title.cache') :: @parent
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('settings/title.cache')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('settings/title.settings')</li>
        <li class="active">@lang('settings/title.cache')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="settings" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('settings/title.cache_clear')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/settings/clearcache'), 'method' => 'GET', 'class' => 'form-horizontal')) !!}
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-4">
								<button type="submit" class="btn btn-success">
									@lang('button.clear_cache')
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
