@if ($ad && $link)
	<div class="ads top-ad">
		<div class="row">
			<div class="col-md-8 col-md-push-2">
				<div class="text-xs-center">
					<a href="{{ $link }}" target="_blank">
						<img src="{{ asset('uploads/ads/'.$ad->image) }}" alt="ad" />
					</a>
				</div>
			</div>
		</div>
	</div>
@endif