@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('block/title.edit')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/blog.css') }}" rel="stylesheet" type="text/css">

    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('block/title.edit')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('block/title.block')</a>
        </li>
        <li class="active">@lang('block/title.edit')</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
			<!-- errors -->
            <div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
           {!! Form::model($block, array('url' => URL::to('admin/block/' . $block->id.'/edit'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
                 <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            {!! Form::text('title', $block->title, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('block/form.title'))) !!}
                        </div>
                        <div class='box-body pad'>
							{!! TemplateHelper::renderWyswingEditor($block->content) !!}
                        </div>
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
						<div class="form-group">
                            {!! Form::label('published', trans('block/form.published')) !!}
                            {!! Form::select('published', array('0' => trans('general.no'), '1' => trans('general.yes')), null, array('class' => 'form-control select2')) !!}
                        </div>
						
                        <div class="form-group">
                            <div class="form-group">
                                {!! Form::text('slug', $block->slug, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('block/form.slug'))) !!}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('block/form.publish')</button>
                            <a href="{!! URL::to('admin/block') !!}"
                               class="btn btn-danger">@lang('block/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                <!-- /.row -->
				<input type="hidden" id="id" name="id" class="form-control" value="{!! $block->id !!}" />
                {!! Form::close() !!}
        </div>
    </div>
    <!--main content ends-->
</section>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit page-->
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>
@stop