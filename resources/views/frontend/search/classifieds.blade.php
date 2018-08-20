@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.search_type_classifieds_page_title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/search.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li>@lang('front/general.search')</li>
		<li>@lang('front/general.search_page_title')</li>
		<li class="active">@lang('front/general.search_page')</li>
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper search-results">
		<hr>
		<div class="welcome">
			<h1>
				@lang('front/general.search_type_classifieds_page_title')
			</h1>
		</div>
		<hr>
		<div class="content">
			<div class="col-sm-12">
				@forelse($results as $item)
					<div class="blogitem-content">
						<h3 class="primary"><a href="{!! TemplateHelper::classifiedsLink($item->id, $item->slug) !!}">{{$item->title}}</a></h3>
						<p>
							{!! TemplateHelper::createShortDescription($item->content) !!}
						</p>
						<p class="additional-post-wrap">
							<span class="additional-post">
								<i class="livicon" data-name="clock" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i>
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
					{!! $results->links() !!}
				</div>
				
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
