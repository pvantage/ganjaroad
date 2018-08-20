@extends('layouts/default')
{{-- Page title --}}
@section('title')
@if($classified->category()){{ $classified->category()->title }} | @endif{{ $classified->title }}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/classifieds.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/fancybox/jquery.fancybox.css') }}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl.carousel/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/owl.carousel/css/owl.theme.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('classifieds') }}">@lang('front/general.classifieds')</a></li>
		@foreach(TemplateHelper::getClassifiedSortedCategories($classified->deafult_categories_ids()) as $category)
			<li><a href="{{ TemplateHelper::classifiedsCategoryLink($category->id, $category->slug) }}">{{ $category->title }}</a></li>
		@endforeach
		<li class="active">{{ $classified->title }}</li>
	</ol>               
@stop


{{-- Page content --}}
@section('content')
    <div class="wrapper nearme-item">
		<hr>
		<div class="welcome">
			<h1>{{$classified->title}}</h1>
		</div>
		<div class="info">
			<span class="additional-post">
				<i class="livicon" data-name="clock" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i>
				<a href="javascript:void(0)">@lang('front/general.posted_ago') {{$classified->created_at->diffForHumans()}}</a>
			</span>
			<span class="pull-right">
				<a href="{{ route('classifieditem/confirm-report/classified', $classified->id) }}" class="modal-link"><i class="fa fa-flag" aria-hidden="true"></i>@lang('front/general.classifieds_report_item')</a>
			</span>
		</div>
		<hr>
		
		@if($errors->has())
			<div class="has-error">
				@foreach ($errors->all() as $error)
					<span class="help-block">{{ $error }}</span>
				@endforeach
			</div>
		@endif
		
        <div class="content">
           <div class="item-wrapper">
				@if($classified->lattitude && $classified->longitude && $classified->images->count())
					<div class="row panel-body">
						<div class="col-md-12">
							<div class="map-wrapper">
								<div id="map"></div>
							</div>
						</div>
					</div>
					<div class="row panel-body">
						<div class="col-md-4">
							<div class="owl-carousel owl-theme">
								@foreach($classified->images as $image)
									<div class="item">
										<a href="{{ asset('uploads/classified/'.$image->item_id) }}/{{ $image->image }}" class="fancybox" rel="gallery">
											<img src="{{ asset('uploads/classified/'.$image->item_id) }}/{{ $image->image }}" alt="slider-image" class="img-responsive" />
										</a>
									</div>
								@endforeach
							</div>
						</div>
						<div class="col-md-8">
				@elseif(!$classified->hide_map && $classified->lattitude && $classified->longitude)
					<div class="row panel-body">
						<div class="col-md-4">
							<div class="map-wrapper">
								<div id="map"></div>
							</div>
						</div>
						<div class="col-md-8">
				@elseif($classified->images->count())
					<div class="row panel-body">
						<div class="col-md-4">
							<div class="owl-carousel owl-theme">
								@foreach($classified->images as $image)
									<div class="item">
										<a href="{{ asset('uploads/classified/'.$image->item_id) }}/{{ $image->image }}" class="fancybox" rel="gallery">
											<img src="{{ asset('uploads/classified/'.$image->item_id) }}/{{ $image->image }}" alt="slider-image" class="img-responsive" />
										</a>
									</div>
								@endforeach
							</div>
						</div>
						<div class="col-md-8">
				@else
				<div class="row panel-body">
					<div class="col-md-12">
				@endif
					<ul class="atr attributes">
						@foreach ($classified->classifiedfieldsvalues as $extrafield)
							<li>
								<div class="row">
									<div class="col-md-5 field-name">
										<label for="{{ $extrafield->code }}">{!! $extrafield->field()->title !!}:</label>
									</div>
									<div class="col-md-7">
										<span class="value">
										@if($extrafield->field()->type == 'price')
											{!! TemplateHelper::convertPrice($extrafield->value) !!}
										@elseif($extrafield->field()->type == 'country')
											{{--*/ $countries = TemplateHelper::getCountriesList(); /*--}}
											{{ $countries[$extrafield->value] }}
										@else
											{!! $extrafield->value !!}
										@endif
										</span>
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
				</div>
			</div>
			<div class="row panel-body">
				<div class="col-md-12">
					<label for="description">@lang('front/general.classified_description'):</label>
					<div class="desc-wrapper">
                        {!! TemplateHelper::getDescription($classified->content) !!}
					</div>
				</div>
			</div> 
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<div class="modal fade" id="modal_popup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

@if($classified->images->count())
<script type="text/javascript" src="{{ asset('assets/vendors/owl.carousel/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.mousewheel-3.0.6.pack.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.fancybox.pack.js') }}"></script>

<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery(".owl-carousel").owlCarousel({
        slideSpeed : 500,
        paginationSpeed : 500,
        singleItem : true,
    });
	jQuery('.fancybox').fancybox({});
});
</script>	
@endif

@if($classified->lattitude && $classified->longitude)
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
<script type="text/javascript">
jQuery( document ).ready(function() {
	var lat = {{ $classified->lattitude }};
	var lng = {{ $classified->longitude }};
			
	var map = new GMaps({
		el: '#map',
		lat: lat,
		lng: lng,
		zoom: 12
	});
			
	var marker = map.addMarker({
		lat: lat,
		lng: lng,
		icon: '{!! TemplateHelper::getSetting('classified_default_icon') !!}',
		draggable:false,
	});
});
</script>
@endif
@stop
