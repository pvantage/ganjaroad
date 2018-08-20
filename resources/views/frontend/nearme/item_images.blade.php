<div class="welcome">
	<h1>@lang('front/general.nearme_images')</h1>
</div>
<hr>
<div class="content">
	<div class="panel-body">
		<div class="row">
			@if($nearme->images->count())
				@foreach($nearme->images as $image)
					<div class="col-md-3 gallery-image">
						<a href="{{ asset('uploads/nearme/'.$image->item_id) }}/{{ $image->image }}" rel="gallery">
							<img src="{{ asset('uploads/nearme/'.$image->item_id) }}/{{ $image->image }}" alt="slider-image" class="img-responsive" />
						</a>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
