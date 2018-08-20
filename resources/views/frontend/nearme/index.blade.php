@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.nearme') @if(isset($nearmecategory))| {{ $nearmecategory->title }} @endif
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/nearme.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		@if(isset($nearmecategory))
			<li><a href="{{ URL::to('nearme') }}">@lang('front/general.nearme')</a></li>
			<li class="active">{{ $nearmecategory->title }}</li>
		@else
			<li class="active">@lang('front/general.nearme')</li>
		@endif
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			@if(isset($nearmecategory))
				<h1>@lang('front/general.nearme_category', ['category' => $nearmecategory->title])</h1>
			@else
				<h1>@lang('front/general.nearme_category_empty')</h1>
			@endif
		</div>
		<hr>
		<div class="content">
			<div class="panel-body map-wrapper">
				<div id="map"></div>
			</div>
			{{--*/ $odd=true /*--}}
			@forelse ($nearme as $item)
				<div class="list-group-item @if($odd) even @else odd @endif row">
					<div class="col-md-12 media col-lg-2 left-side">
						@if($item->getIcon())
							<img src="{!! $item->getIcon() !!}" class="map-icon" />
						@else
							<i class="fa fa-map-marker"></i>  
						@endif
						<p class="list-text address">{!! $item->formatAddressShort() !!}</p>
					</div>
					<div class="col-md-12 col-lg-8 col-lg-offset-1">
						<h4 class="list-group-item-heading list-heading">{!! $item->title !!}</h4>
						<p class="list-group-item-text list-text">
							{!! TemplateHelper::createShortDescription($item->content) !!}
						</p>
					</div>
					<div class="col-md-12 col-lg-2 col-lg-offset-1">
						<a href="{{ TemplateHelper::nearMeLink($item->id, $item->slug) }}"><button type="button" class="btn btn-default btn-sm btn-block">@lang('front/general.read_more') </button></a>
					</div>
				</div>
				{{--*/ $odd = !$odd /*--}}
			@empty
				<h3>@lang('front/general.nearme_empty')</h3>
			@endforelse
			
			<div class="pull-right">
				{!! $nearme->links() !!}
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
<script type="text/javascript">
jQuery( document ).ready(function() {	
	var lat = @if($location['lat']) {!! $location['lat'] !!} @else {!! TemplateHelper::getDefaultLattitude() !!} @endif;
	var lng = @if($location['lon']) {{ $location['lon'] }} @else {!! TemplateHelper::getDefaultLongitude() !!} @endif;
	
	var map = new GMaps({
		el: '#map',
		lat: lat,
		lng: lng,
		zoom: 12
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
	
	@forelse ($nearme as $item)
        var title = addslashes("{{ $item->title }}");
        var text = addslashes("{{ $item->formatAddressShort() }}");
		var marker = map.addMarker({
			lat: {{ $item->lattitude }},
			lng: {{ $item->longitude }},
			icon: '{!! $item->getIcon() !!}',
			draggable:false,
			infoWindow: {
				content: '<p><a href="{{ TemplateHelper::nearMeLink($item->id, $item->slug) }}">' + title + '</a></p><p>' + text + '</p>'
			}
		});	
	@endforeach
});
function addslashes(str) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}
</script>
@stop
