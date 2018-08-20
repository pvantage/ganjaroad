@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('blog/title.edit')
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
    <h1>@lang('blog/title.edit')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('blog/title.blog')</a>
        </li>
        <li class="active">@lang('blog/title.edit')</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
			<div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
           {!! Form::model($blog, array('url' => URL::to('admin/blog/' . $blog->id.'/edit'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=>trans('blog/form.ph-title'))) !!}
                        </div>
                        <div class='box-body pad'>
                            {!! TemplateHelper::renderWyswingEditor($blog->content) !!}
                        </div>
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('blog_category_id', trans('blog/form.ll-postcategory')) !!}
                            {!! Form::select('blog_category_id',$blogcategory ,null, array('class' => 'form-control select2', 'placeholder'=>trans('blog/form.select-category')))!!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('published', trans('blog/form.published')) !!}
                            {!! Form::select('published', array('0' => trans('general.no'), '1' => trans('general.yes')), null, array('class' => 'form-control select2')) !!}
                        </div>						
						
						<div class="form-group">
							{!! Form::label('published_from', trans('blog/form.published_from')) !!}
							{!! Form::DateTimeInput('published_from')!!}
						</div>
						
						<div class="form-group">
							{!! Form::label('published_to', trans('blog/form.published_to')) !!}
							{!! Form::DateTimeInput('published_to')!!}
						</div>
						
                        <div class="form-group">
                            {!! Form::text('tags', $blog->tagList, array('class' => 'form-control input-lg', 'data-role'=>"tagsinput", 'placeholder'=>trans('blog/form.tags')))!!}
                        </div>
                        <div class="form-group">
                            <label>@lang('blog/form.lb-featured-img')</label>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <span class="btn btn-primary btn-file">
                                    <span class="fileupload-new">@lang('blog/form.select-file')</span>
                                    <span class="fileupload-exists">@lang('blog/form.change')</span>
                                     {!! Form::file('image', null, array('class' => 'form-control')) !!}
                                </span>
                                @if(!empty($blog->image))
                                    <span class="fileupload-preview">
                                        <img src="{{URL::to('uploads/blog/'.$blog->image)}}" class="img-responsive" alt="Image">
                                    </span>
                                @endif
                                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                            </div>
                        </div>
						
						<div class="form-group">
                            {!! Form::label('canonical', trans('blog/form.canonical')) !!}
                            {!! Form::UrlInput('canonical', trans('nearme/form.canonical_placeholder')) !!}
                        </div>
						
						<div class="form-group">
							{!! Form::label('meta_description', trans('blog/form.meta-desc')) !!}
							{!! Form::textarea('meta_description', null, array('class' => 'form-control input-lg', '', 'placeholder'=> trans('blogcategory/form.meta-desc'))) !!}
						</div>
							
						<div class="form-group">
							{!! Form::label('meta_keywords', trans('blog/form.meta-keywords')) !!}
							{!! Form::textarea('meta_keywords', null, array('class' => 'form-control input-lg', '', 'placeholder'=> trans('blogcategory/form.meta-keywords'))) !!}
						</div>
						
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('blog/form.save')</button>
                            <a href="{{ URL::to('admin/blog') }}" class="btn btn-danger">@lang('blog/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                <!-- /.row -->
                {!! Form::close() !!}
        </div>
    </div>
    <!--main content ends-->
</section>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit blog-->
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}" type="text/javascript" ></script>
<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>

{!! TemplateHelper::includeDateScript() !!}

<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery(".url-type").on("click", function(){
		var wrapper = jQuery(this).closest('.url-wrapper');
		wrapper.find('.url-type').removeClass('selected');
		jQuery(this).addClass('selected');
		
		var url_type = jQuery(this).text();
		var value = wrapper.find('.temp_url').val();
		jQuery('#url_button').text(url_type);
		
		if(value) wrapper.find('.real-value').val(url_type + value);
		else wrapper.find('.real-value').val('');
	});
	
	jQuery(".temp_url").change(function() {
		var wrapper = jQuery(this).closest('.url-wrapper');
		var url_type = wrapper.find('.url-type.selected').text();
		var value = jQuery(this).val();
		
		if(value) wrapper.find('.real-value').val(url_type + value);
		else wrapper.find('.real-value').val('');
	});
});
</script>
</script>
@stop