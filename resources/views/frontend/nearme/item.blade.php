@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ $nearme->category->title }} | {{ $nearme->title }}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/nearme.css') }}">

	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/fancybox/jquery.fancybox.css') }}" media="screen" />
	<link href="{{ asset('assets/vendors/ninja-slider/ninja-slider.css') }}" rel="stylesheet" type="text/css" />
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('nearme') }}">@lang('front/general.nearme')</a></li>
		<li><a href="{{ TemplateHelper::nearMeCategoryLink($nearme->category->id, $nearme->category->slug)}}">{{ $nearme->category->title }}</a></li>
		<li class="active">{{ $nearme->title }}</li>
	</ol>               
@stop


{{-- Page content --}}
@section('content')
    <div class="wrapper nearme-item">
		@if($deals)
			<ul class="nav nav-tabs" role="tablist" id="nearme_tabs">
				<li class="active" role="presentation">
					<a href="#tab1" role="tab" data-toggle="tab">@lang('front/general.nearme_details')</a>
				</li>
				@if($menu && $menu->count())
					<li role="presentation">
						<a href="#tab0" role="tab" data-toggle="tab">@lang('front/general.nearme_menu')</a>
					</li>
				@endif
				@if($nearme->images->count())
					<li>
						<a href="#tab2" role="tab" data-toggle="tab">@lang('front/general.nearme_images')</a>
					</li>
				@endif
				@if($dispensaries && $dispensaries->count())
					<li>
						<a href="#tab3" role="tab" data-toggle="tab">@lang('front/general.nearme_dispensaries')</a>
					</li>
				@endif
				@if($delivery && $delivery->count())
					<li>
						<a href="#tab4" role="tab" data-toggle="tab">@lang('front/general.nearme_delivery')</a>
					</li>
				@endif
				@if($deals && $deals->count())
					<li>
						<a href="#tab5" role="tab" data-toggle="tab">@lang('front/general.nearme_deals')</a>
					</li>
				@endif
			</ul>
			<div class="tab-content mar-top">
				<div id="tab1" class="tab-pane fade active in">
					@include('frontend/nearme/item_details')
				</div>
				@if($menu && $menu->count())
					<div id="tab0" class="tab-pane fade">
						@include('frontend/nearme/item_menu')
					</div>
				@endif
				@if($nearme->images->count())
					<div id="tab2" class="tab-pane fade">
						@include('frontend/nearme/item_images')
					</div>
				@endif
				@if($dispensaries && $dispensaries->count())
					{{--*/ $items = $dispensaries /*--}}
					<div id="tab3" class="tab-pane fade">
						@include('frontend/nearme/item_deals')
					</div>
				@endif
				@if($delivery && $delivery->count())
					{{--*/ $items = $delivery /*--}}
					<div id="tab4" class="tab-pane fade">
						@include('frontend/nearme/item_deals')
					</div>
				@endif
				@if($deals && $deals->count())
					{{--*/ $items = $deals /*--}}
					<div id="tab5" class="tab-pane fade">
						@include('frontend/nearme/item_deals')
					</div>
				@endif
			</div>
		@else
			@include('frontend/nearme/item_details')
		@endif
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.mousewheel-3.0.6.pack.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.fancybox.pack.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/ninja-slider/ninja-slider.js') }}"></script>

<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery('.gallery-image a').fancybox();

	jQuery('#nearme_tabs li').click(function (e) {
		e.preventDefault();
		jQuery('.nav-tabs a').removeClass('active');
		jQuery(this).tab('show')
	});
	
	var lat = @if($location['lat']) {!! $location['lat'] !!} @else {!! TemplateHelper::getDefaultLattitude() !!} @endif;
	var lng = @if($location['lon']) {{ $location['lon'] }} @else {!! TemplateHelper::getDefaultLongitude() !!} @endif;
	
	var map = new GMaps({
		el: '#map',
		lat: {!! $nearme->lattitude !!},
		lng: {!! $nearme->longitude !!},
		zoom: 12
    });
	
	var marker = map.addMarker({
		lat: {!! $nearme->lattitude !!},
		lng: {!! $nearme->longitude !!},
		icon: '{!! $nearme->getIcon() !!}',
		draggable:false
	});	
	
	var marker = map.addMarker({
		lat: lat,
		lng: lng,
		icon: '{!! TemplateHelper::getSetting('nearme_default_icon') !!}',
		draggable:false,
		infoWindow: {
			content: '<p>@lang('front/general.your_location')</p>'
		}
	});
	
	jQuery(".weed-select").change(displayVals);

	jQuery('.btn-show-prices').click(function() {
		jQuery(this).toggleClass('btn-open');
		if (jQuery(this).hasClass('btn-open')) {
			jQuery(this).text("@lang('front/general.show_all_prices')");
        } else {
			jQuery(this).text("@lang('front/general.hide_all_prices')");
        }
		jQuery('.gallery-item-wrapper').toggleClass('open');
	});

	jQuery('.two-detail-box')
		.mouseenter(function() {
			if (!jQuery(this).hasClass('custom-tip')) {
				jQuery(this).clone(true).addClass('custom-tip').appendTo(this);
			}
		})
		.mouseleave(function() {
			jQuery('.custom-tip').remove();
		});
});
function displayVals() {
	jQuery('.name-wrapper').fadeOut('fast');
	var singleValues = jQuery(".weed-select").val();
	if(singleValues) {
		setTimeout(function() {
			jQuery('.' + singleValues).fadeIn();
		}, 200);
	} else {
		setTimeout(function() {
			jQuery('.name-wrapper').fadeIn();
		}, 200);
	}
}
function lightbox(idx) {
	var ninjaSldr = document.getElementById("ninja-slider");
	ninjaSldr.parentNode.style.display = "block";
	nslider.init(idx);
	var fsBtn = document.getElementById("fsBtn");
	fsBtn.click();
}

function fsIconClick(isFullscreen) { 
	var ninjaSldr = document.getElementById("ninja-slider");
	ninjaSldr.parentNode.style.display = isFullscreen ? "block" : "none";
}
</script>
@stop
