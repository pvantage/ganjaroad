@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.classifieds') | {{ $category->title }}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-treeview/css/bootstrap-treeview.css') }}">
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/classifieds.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('classifieds') }}">@lang('front/general.classifieds')</a></li>
		@foreach(TemplateHelper::getClassifiedsParentCategories($category->id) as $parent)
			<li><a href="{{ TemplateHelper::classifiedsCategoryLink($parent->id, $parent->slug) }}">{{ $parent->title }}</a></li>
		@endforeach
		<li class="active">{{ $category->title }}</li>
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>{{ $category->title }}</h1>
		</div>
		<hr>
		<div class="content">
			<div class="col-sm-12">
				@forelse($classifieds as $item)
					<div class="blogitem-content">
						<h3 class="primary"><a href="{!! TemplateHelper::classifiedsLink($item->id, $item->slug) !!}">{{$item->title}}</a></h3>
						<p>
							{!! TemplateHelper::createShortDescription($item->content) !!}
						</p>
						<p class="additional-post-wrap">
							<span class="additional-post">
								<a href="javascript:void(0)">@lang('front/general.posted_ago') {{$item->created_at->diffForHumans()}}</a>
							</span>
						</p>
						<p class="text-right">
							<a href="{!! TemplateHelper::classifiedsLink($item->id, $item->slug) !!}" class="btn btn-primary text-white">@lang('front/general.read_more')</a>
						</p>
					</div>
				@empty
					@lang('front/general.no_classifieds')
				@endforelse
				
				<div class="pull-right">
					{!! $classifieds->links() !!}
				</div>
				
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
