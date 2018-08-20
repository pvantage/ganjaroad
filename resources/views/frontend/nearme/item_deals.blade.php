{{--*/ $odd=true /*--}}
@forelse ($items as $item)
	<div class="list-group-item @if($odd) even @else odd @endif row">
		<div class="col-md-12 media col-lg-2 left-side">
			@if($item->image())
				<img src="{{ URL::to('uploads/nearme') }}/{{ $item->id }}/{{ $item->image() }}" alt="slider-image" class="img-responsive">
			@else
				
			@endif
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

@endforelse