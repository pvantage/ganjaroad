@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @lang('front/title.home')
@parent
@stop

@section('meta')
	<meta name="description" content="{{ TemplateHelper::getSetting('homepage_meta_description') }}">
	<meta name="keywords" content="{{ TemplateHelper::getSetting('homepage_meta_keywords') }}">
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/vendors/jquery-easy-ticker/jquery.easy-ticker.css') }}" rel="stylesheet" type="text/css">
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
	</ol>
@stop

@section('content')
	<div class="row changelocation-wrapper">
		<div class="col-md-12 col-lg-12">
			<form action="{{ URL::to('changelocation') }}" method="get">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input class="form-control" id="changelocation" name="changelocation" type="text" placeholder="@lang('front/general.changelocation')" value="" />
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-6">
			<div class="main_col_first_top">
				<div class="block block_search_near_me">
					<h2 class="block-title"><img src="{{ asset('assets/images/compass.png') }}" srcset="{{ asset('assets/images/compass.png') }}, {{ asset('assets/images/compass@2x.png 2x') }}" alt="Blok Near Me" width="29" height="29">@lang('front/general.front_near_me')</h2>
					<div class="content">
						<div class="block-search">
							<form action="{{ URL::to('changenearmelocation') }}" method="get">
								<input type="hidden" name="_token" value="{{ csrf_token() }}" />
								<label class="sr-only" for="nearmelocation">@lang('front/general.search_near_me_placeholder')</label>
								<input class="form-control" id="nearmelocation" name="nearmelocation" type="text" placeholder="@lang('front/general.search_near_me_placeholder')" value="" />
								<button class="btn btn-success-outline" type="submit"><img src="{{ asset('assets/images/ico-search.png') }}" alt=""><span class="sr-only">@lang('front/general.search_near_me_placeholder')</span></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12 col-lg-6">
			<div class="main_col_secound_top">
				<div class="block block_search_classifieds">
					<h3 class="block-title">@lang('front/general.classifieds')</h3>
					<div class="content">
						<div class="block-search">
							<form action="{{ URL::to('changeclassifiedslocation') }}" method="get" id="search">
								<div class="col-sm-label">
									<label for="search_classifieds">@lang('front/general.search_classifieds_in'):</label>
								</div>
								<div class="col-sm-selectpicker">
									{{--*/ $options = TemplateHelper::getClassifiedsCityDropdownOptions() /*--}}
									{!! Form::select('city', $options, session('current_city'), array('class' => 'form-control selectpicker', 'name' => 'search_classifieds', 'id' => 'search_classifieds')) !!}
								</div>
								<div id="classifieds_input">
									<input id="search-terms" name="search-terms" placeholder="@lang('front/general.search_temrs')" type="text">
								</div>
								<div class="col-sm-btn">
									<div id="classifieds_label">
										<label id="search-label" for="search-terms">@lang('front/general.search')</label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-6">
			<div class="main_col_first_middle">
				<div class="block_action">
					<a href="{!! URL::to('newnearme') !!}" class="add_new_item">@lang('front/general.add_new_nearme')</a>
				</div>
				<div class="card_list">
					{{--*/ $categories = TemplateHelper::getNearMeCategories() /*--}}
					@if ($categories)
						@foreach ($categories as $category)
							<a href="{{ TemplateHelper::nearMeCategoryLink($category->id, $category->slug) }}" class="card card-inverse">
								@if($category->image)
									<img class="card-img" src="{{ TemplateHelper::getNearMeCategoryImageDir().$category->image }}" alt="Clinic, dispensary, ambulatory">
								@endif
								<div class="card-img-overlay {{ $category->slug }}">
									<h4 class="card-title">
										<img src="{{ $category->getIcon() }}" alt="{{ $category->title }}" />
										<span class="title">{{ $category->title }}</span>
									</h4>
								</div>
							</a>
						@endforeach
					@endif
				</div>
			</div>
		</div>

		<div class="col-md-12 col-lg-6">
			<div class="main_col_secound_middle">
				<div class="block_action">
					<a href="{!! URL::to('newclassified') !!}" class="add_new_item">@lang('front/general.add_new_classifieds')</a>
				</div>
			</div>
			<div class="request-areaadd">
				<a href="{{ url('contact') }}">@lang('front/general.request_add_area')</a>
			</div>
			<div class="main_col_secound_middle" id="classifieds_categories">
				@include('classifieds_categories')
			</div>
		</div>
	</div>

	{{--*/ $news = TemplateHelper::getBlogNews() /*--}}
	@if ($news->count())
		<section class="section-new-topic">
			<div class="block-new-topic">
				<div class="row news-wrapper">
					<div class="col-sm-12 news-slider">
						<ul>
							@foreach ($news as $item)
								<li>
									<div class="card card-block item-new-topic">
										<h3 class="card-title">{{ $item->title }}</h3>
										<ul>
											<li>
												<p class="card-text">
													{{--*/ TemplateHelper::createShortDescription($item->content, true) /*--}}
													<a href="{!! TemplateHelper::blogLink($item->id, $item->slug) !!}" class="link read-more">@lang('front/general.read_more')</a>
												</p>
											</li>
										</ul>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</section>
	@endif
@stop

@section('footer_scripts')
<!-- begining of page level js -->
<script src="{{ asset('assets/vendors/jquery-easy-ticker/jquery.easy-ticker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/classie/classie.js') }}" type="text/javascript"></script>
<script type="text/javascript">
jQuery('.news-slider').easyTicker({
	direction: 'up',
	easing: 'swing',
	visible: 2,
	interval: 4000
});
jQuery( document ).ready(function() {

    var searchClassifieds = function() {
        var category = jQuery('#search_classifieds').val();
        var query = jQuery('#search-terms').val();
        var url = "{{ URL::to('search/type/classifieds/') }}";
        url += '/query/' + query + '/category/' + category;

        if(query) window.location.href = url;
    };

    // get vars
	var searchEl = document.querySelector("#classifieds_input");
	var labelEl = document.querySelector("#classifieds_label");

	// register clicks and toggle classes
	labelEl.addEventListener("click", function(){
		if (classie.has(searchEl, "focus")) {
			searchClassifieds();
		} else {
			classie.add(searchEl, "focus");
			classie.add(labelEl, "active");
		}
	});

    jQuery('#classifieds_input').keyup(function(event){
        if(event.keyCode == 13) {
            searchClassifieds();
        }
    });

	jQuery('#search_classifieds').on('change', function() {
		var city = jQuery(this).val();

		if(city) {
			var html = '';
			jQuery.showLoader();
			jQuery.ajax({
				url: '{!! URL::to('changecity') !!}',
				type: 'post',
				data: {'city': city, '_token': jQuery('input[name=_token]').val()},
				success: function(data){
					html = data;
				}
			}).done(function() {
				if(html) {
					jQuery('#classifieds_categories').fadeOut(function() {
						jQuery('#classifieds_categories').html(html);
						jQuery('#classifieds_categories').fadeIn(function() {
							jQuery.hideLoader();
						});
					});
				} else {
					jQuery.hideLoader();
				}
			});
		}
	});

	// register clicks outisde search box, and toggle correct classes
	document.addEventListener("click",function(e){
		var clickedID = e.target.id;
		if (clickedID != "search-terms" && clickedID != "search-label") {
			if (classie.has(searchEl,"focus")) {
				classie.remove(searchEl, "focus");
				classie.remove(labelEl, "active");
			}
		}
	});
});
</script>
@stop
