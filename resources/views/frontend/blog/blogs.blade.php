@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@if(isset($blog_category))
		{{ $blog_category->title }}
	@else
		@lang('front/general.blog')
	@endif
@parent
@stop

@section('meta')
	@if(isset($blog_category))
		<meta name="title" content="{{ $blog_category->title }}">
		<meta name="description" content="{{ $blog_category->meta_description }}">
		<meta name="keywords" content="{{ $blog_category->meta_keywords }}">
	@endif
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/blog.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">@lang('front/general.blog')</li>
	</ol>               
@stop

@section('leftcol')
	@section('leftcol_content')
		<div class="block">
			<h3 class="block-title">@lang('front/general.blog_categories')</h3>
		</div>
		<div class="content">
			{{--*/ $categories = TemplateHelper::getBlogCategories() /*--}}
			<dl>
				@forelse ($categories as $category)
					<dt><dd><a href="{{ TemplateHelper::blogCategoryLink($category->id, $category->slug) }}">{{ $category->title }}</a></dd></dt>
				@empty
				
				@endforelse
			</dl>
		</div>
	@stop
	@parent
@stop

@section('rightcol')
	@section('rightcol_content')
		<div class="block">
			{!! TemplateHelper::renderRightBlock() !!}
			{!! TemplateHelper::generateAdd('right') !!}
		</div>
	@stop
	@parent
@stop


{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>
			@if(isset($blog_category->title))
				{{ $blog_category->title }}
			@else
				@lang('front/general.blog')
			@endif
			</h1>
		</div>
		<hr>
		<div class="content">
			@forelse ($blogs as $blog)
				<div class="row">
					@if($blog->image)
					<div class="col-sm-6">
						<a href="{!! TemplateHelper::blogLink($blog->id, $blog->slug) !!}">
							<img src="{{ TemplateHelper::getBlogImageDir().$blog->image }}" class="img-responsive" alt="{{ $blog->title }}" />
						</a>
					</div>
					<div class="col-sm-6">
					@else
					<div class="col-sm-12">
					@endif
						<div class="blogitem-content">
							<h3 class="primary"><a href="{!! TemplateHelper::blogLink($blog->id, $blog->slug) !!}">{{$blog->title}}</a></h3>
							{!! TemplateHelper::createShortDescription($blog->content, true) !!}
							<p>
								@if($blog->tags->count())
									{{--*/ $i = 0 /*--}}
									<strong>@lang('front/general.tags'): </strong>
									@foreach($blog->tags as $tag)
										<a href="{!! TemplateHelper::blogTagLink($tag) !!}">{{ $tag }}</a>@if(++$i < $blog->tags->count()),@endif
									@endforeach
								@else
								@endif
							</p>
							<p class="additional-post-wrap">
								<span class="additional-post">
									<i class="livicon" data-name="clock" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i><a href="#"> {{$blog->created_at->diffForHumans()}}</a>
								</span>
								@if((bool)TemplateHelper::enableBlogComments($blog->category->id))
									<span class="additional-post">
										<i class="livicon" data-name="comment" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i><a href="#"> {{$blog->comments->count()}} comments</a>
									</span>
								@endif
							</p>
							 <p class="text-right">
                                <a href="{!! TemplateHelper::blogLink($blog->id, $blog->slug) !!}" class="btn btn-primary text-white">@lang('front/general.see_details')</a>
                            </p>
						</div>
					</div>
				</div>
				<hr>
			@empty
				<h3>@lang('front/general.blog_empty')</h3>
			@endforelse
			<div class="pull-right">
				{!! $blogs->links() !!}
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
