<div class="welcome">
	<h1>{{$nearme->title}}</h1>
</div>
<hr>
<div class="content">

	<div class="panel-body">
		<div class="row">
			<div class="col-md-7">
				@if($nearme->image)
					<img src="{{ asset('uploads/nearme/'.$nearme->id) }}/{{ $nearme->image }}" class="img-responsive main-image" />
				@endif
				<ul class="address-details">
					<li class="address">@lang('front/general.nearme_email'): {{ $nearme->email }}</li>
					<li class="address">@lang('front/general.nearme_phone'): {{ $nearme->phone }}</li>
					<li class="address">{!! $nearme->formatAddressFull() !!}</li>
					@if($nearme->url)
						@if((!filter_var($nearme->url, FILTER_VALIDATE_URL) === false))
							<li class="url">
								@lang('front/general.nearme_url'): <a href="{!! $nearme->url !!}" rel="nofollow" target="_blank">{!! $nearme->url !!}</a>
							</li>
						@else
							<li class="url">@lang('front/general.nearme_url'): {!! $nearme->url !!}</li>
						@endif
					@endif
					<li class="last-updated">@lang('nearme/form.last_updated'): {!! TemplateHelper::nearmeEditFormatDate($nearme->updated_at) !!}</li>
				</ul>
			</div>
			
			<div class="col-md-5">
				<ul class="nearme-features">
					@if($nearme->atm)
						<li class="atm"><i class="fa fa-truck" aria-hidden="true"></i>@lang('front/general.nearme_delivery')</li>
					@endif
					@if($nearme->atm)
						<li class="atm"><i class="fa fa-usd" aria-hidden="true"></i>@lang('front/general.nearme_atm')</li>
					@endif
					@if($nearme->min_age)
						<li class="atm"><i class="fa fa-users" aria-hidden="true"></i><span class="name">@lang('front/general.nearme_age'):</span> {{ $nearme->min_age }}+</li>
					@endif
					@if($nearme->wheelchair)
						<li class="atm"><i class="fa fa-wheelchair" aria-hidden="true"></i>@lang('front/general.nearme_wheelchair')</li>
					@endif
					@if($nearme->security)
						<li class="atm"><i class="fa fa-lock" aria-hidden="true"></i>@lang('front/general.nearme_security')</li>
					@endif
					@if($nearme->credit_cards)
						<li class="atm"><i class="fa fa-credit-card" aria-hidden="true"></i>@lang('front/general.nearme_cc')</li>
					@endif
				</ul>
			</div>
		</div>
		
		<div class="row">
			@if($nearme->hours)
				<div class="col-md-8">
			@else
				<div class="col-md-12">
			@endif
				<div class="map-wrapper item-map">
					<div id="map"></div>
				</div>
			</div>
			<div class="col-md-4">
				@if($nearme->hours)
					{!! TemplateHelper::renderHours($nearme->hours) !!}
				@endif
				@if($nearme->facebook || $nearme->instagram || $nearme->twitter)
					<div class="row">
						<ul class="socials">
							@if($nearme->facebook)
								<li class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i> <a href="{{ $nearme->facebook }}" target="_blank">@lang('front/general.nearme_facebook')</a></li>
							@endif
							
							@if($nearme->instagram)
								<li class="instagram"><i class="fa fa-instagram" aria-hidden="true"></i> <a href="{{ $nearme->instagram }}" target="_blank">@lang('front/general.nearme_instagram')</a></li>
							@endif
							
							@if($nearme->twitter)
								<li class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i> <a href="{{ $nearme->twitter }}" target="_blank">@lang('front/general.nearme_twitter')</a></li>
							@endif
						</ul>
					</div>
				@endif
			</div>
		</div>
			
		<div class="row">
			<div class="col-md-12 description">
                {!! TemplateHelper::getDescription($nearme->content) !!}
			</div>
			@if($nearme->first_time)
				<div class="col-md-12 description">
					<h4>
						@lang('front/general.nearme_first_time_header')
					</h4>
					<p>{!! $nearme->first_time !!}</p>
				</div>
			@endif
		</div> 
	</div> 
</div>