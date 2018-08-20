@if ($ad && $link)
	<div class="advertisment">
		<div class="row">
			<div class="col-sm-8 col-sm-push-2">
				<div class="ads text-xs-center">
					<a href="{{ $link }}" target="_blank">
						<img src="{{ asset('uploads/ads/'.$ad->image) }}" alt="ad" />
					</a>
				</div>
			</div>
		</div>
	</div>
@endif