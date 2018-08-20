<nav class="navbar navbar-full navbar-absolut-top"  role="navigation">
	<div class="container-button">
		<button class="c-hamburger c-hamburger--htx" type="button" data-toggle="collapse" data-target="#nav-content" tabindex="0"><span>@lang('front/general.toggle')</span></button>
	</div>
	<div class="container">
		<h1 class="navbar-brand">
			<a href="{{ URL::to('/') }}">
				<img src="{{ asset('assets/images/logo.png') }}" srcset="{{ asset('assets/images/logo.png 1x') }}, {{ asset('assets/images/logo@2x.png 2x') }}" alt="Ganjaroad" width="282" height="51" />
			</a>
		</h1>

		<div class="collapse navbar-toggleable-md" id="nav-content">
			<ul class="nav navbar-nav pull-md-right">
				@if(User::guest())
					<li class="nav-item @if (Request::is('login')) active @endif">
						<a class="nav-link" href="{{ URL::to('login') }}">@lang('front/general.login')</a>
					</li>
					<li class="nav-item @if (Request::is('register')) active @endif">
						<a class="nav-link" href="{{ URL::to('register') }}">@lang('front/general.create_account')</a>
					</li>
					<li class="nav-item @if (Request::is('forgot-password')) active @endif">
						<a class="nav-link" href="{{ URL::to('forgot-password') }}">@lang('front/general.forgot_password')</a>
					</li>
				@else
					<li class="nav-item">
						<a class="nav-link" href="{{ URL::to('my-account') }}">@lang('front/general.my_account')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ URL::to('logout') }}">@lang('front/general.logout')</a>
					</li>
				@endif
			</ul>
		</div>
	</div>
</nav>