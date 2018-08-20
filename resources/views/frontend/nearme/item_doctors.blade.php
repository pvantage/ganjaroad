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

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl.carousel/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl.carousel/css/owl.theme.css') }}">
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
		<div class="doctors-wrapper">
			@include('frontend/nearme/item_details')
		</div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>

@if($nearme->images->count())
<script type="text/javascript" src="{{ asset('assets/vendors/owl.carousel/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery(".owl-carousel").owlCarousel({
        slideSpeed : 500,
        paginationSpeed : 500,
        singleItem : true,
    });
});
</script>	
@endif

<script type="text/javascript">
jQuery( document ).ready(function() {	
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
});
</script>
@stop
