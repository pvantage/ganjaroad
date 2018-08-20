@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.search_type_blogs_page_title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-treeview/css/bootstrap-treeview.css') }}">
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/search.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li>@lang('front/general.search')</li>
		<li>@lang('front/general.search_page_title')</li>
		<li class="active">@lang('front/general.search_blog')</li>
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper search-results">
		<hr>
		<div class="welcome">
			<h1>
				@lang('front/general.search_type_blogs_page_title')
			</h1>
		</div>
		<hr>
		<div class="content">
			@forelse ($results as $blog)
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
								<strong>@lang('front/general.tags'): </strong>
								@forelse($blog->tags as $tag)
									<a href="{!! TemplateHelper::blogTagLink($tag) !!}">{{ $tag }}</a>,
								@empty
									@lang('front/general.no_tags')
								@endforelse
							</p>
							<p class="additional-post-wrap">
								<span class="additional-post">
									<i class="livicon" data-name="clock" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i><a href="#"> {{$blog->created_at->diffForHumans()}}</a>
								</span>
								<span class="additional-post">
									<i class="livicon" data-name="comment" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i><a href="#"> {{$blog->comments->count()}} comments</a>
								</span>
							</p>
							 <p class="text-right">
                                <a href="{!! TemplateHelper::blogLink($blog->id, $blog->slug) !!}" class="btn btn-primary text-white">@lang('front/general.read_more')</a>
                            </p>
						</div>
					</div>
				</div>
				<hr>
			@empty
				<h3>@lang('front/general.blog_empty')</h3>
			@endforelse
			<div class="pull-right">
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
