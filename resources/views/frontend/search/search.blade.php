@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.search_page_title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-treeview/css/bootstrap-treeview.css') }}">
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/classifieds.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/search.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">@lang('front/general.search_page_title')</li>
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper search-results">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.search_page_title')</h1>
		</div>
		<hr>
		<div class="content">
			<div class="col-sm-12">
				<div class="row">
					@if($nearmes)
						<h2>@lang('front/general.search_page_nearme')</h2>
						@foreach($nearmes as $nearme)
							<div class="col-sm-12">
								<ul class="nearme-list tree">
									<li>
										<a href="javascript:void(0)" class="search-nearme link">
											{{ $nearme['category']->title }}
										</a>
										<span class="open-link">
											@if(count($nearme['items']))
												<a href="{{ route('type/stype/query/squery', ['nearme', $search_query]) }}">
													@lang('front/general.search_view_all') ({{ count($nearme['items']) }}) @lang('front/general.search_results')
												</a>
											@else
												(0) @lang('front/general.search_results')
											@endif
										</span>
										<ul>
											<li>
												
											</li>
											{{--*/ $i = 0 /*--}}
											@forelse($nearme['items'] as $item)
												@if(++$i > $limit) @break @endif
												<li><a href="{{ TemplateHelper::nearMeLink($item->id, $item->slug) }}">{{ $item->title }}</a></li>
											@empty
											
											@endforelse
											@if(count($nearme['items']) > $limit)
												<li><span class="more">+ {{ count($nearme['items']) - $limit }} @lang('front/general.search_items')</span></li>
											@endif
										</ul>
									</li>
								</ul>
							</div>
						@endforeach
					@endif
					
					<div class="col-sm-12">
						<h2>@lang('front/general.search_page_classifieds')</h2>
						@foreach($categories as $sub)
							@if(!$sub->classifiedsQuery($search_query)->count()) @continue; @endif
							<ul class="classifieds-list tree">
								<li class="root">
									<a href="{!! TemplateHelper::classifiedsCategoryLink($sub->id, $sub->slug) !!}">{{ $sub->title }}</a>
									@if(isset($sub['childrens']))
										<ul>
											{!! TemplateHelper::generateClassifiedsCategories($sub['childrens'], $search_query) !!}
										</ul>
									@endif
								</li>
							</ul>
						@endforeach
					</div>
			
					<div class="col-sm-12">
						<h2>@lang('front/general.search_page_others')</h2>
						<ul class="other-list">
							@forelse($pages as $item)
								<li><a href="{!! TemplateHelper::pageLink($item->id, $item->url) !!}">{{ $item->title }}</a></li>
							@empty
							@endif
							@forelse($blogs as $item)
								<li><a href="{!! TemplateHelper::blogLink($item->id, $item->slug) !!}">{{ $item->title }}</a></li>
							@empty
							@endif
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-treeview/js/bootstrap-treeview.js') }}" type="text/javascript"></script>

<script type="text/javascript">
jQuery( document ).ready(function() {
	<?php /* open by default
	jQuery('.collapse').collapse();
	*/ ?>
	
	//Initialization of treeviews
	jQuery('.tree').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});
});

jQuery.fn.extend({
	treed: function (o) {
      
		var openedClass = 'glyphicon-folder-open';
		var closedClass = 'glyphicon-folder-close';
      
		if (typeof o != 'undefined'){
			if (typeof o.openedClass != 'undefined'){
				openedClass = o.openedClass;
			}
			if (typeof o.closedClass != 'undefined'){
				closedClass = o.closedClass;
			}
		};
      
		//initialize each of the top levels
		var tree = jQuery(this);
		tree.addClass("tree");
		tree.find('li').has("ul").each(function () {
			var branch = jQuery(this); //li with children ul
			branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
			branch.addClass('branch');
			branch.on('click', function (e) {
				if (this == e.target) {
					var icon = jQuery(this).children('i:first');
					icon.toggleClass(openedClass + " " + closedClass);
					jQuery(this).children().children().toggle();
				}
			});
			
			//open root dir
			if(jQuery(this).hasClass('root')) {
				jQuery(this).click();
			}
			
			//jQuery(this).click();
			@if(isset($category))

			@else
				branch.children().children().toggle(); //close all
			@endif
        });
        
		//fire event from the dynamically added icon
		tree.find('.branch .indicator').each(function(){
			jQuery(this).on('click', function () {
				jQuery(this).closest('li').click();
			});
		});
        
		//fire event to open branch if the li contains an anchor instead of text
		tree.find('.branch>a').each(function () {
			jQuery(this).on('click', function (e) {
				jQuery(this).closest('li').click();
				e.preventDefault();
			});
		});
        
		//fire event to open branch if the li contains a button instead of text
		tree.find('.branch > button').each(function () {
			jQuery(this).on('click', function (e) {
				jQuery(this).closest('li').click();
				e.preventDefault();
			});
		});
    }
});
</script>
@stop