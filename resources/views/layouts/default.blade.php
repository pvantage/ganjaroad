<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@section('meta')
	
	@show
	
	<meta property="og:site_name" content="Ganjaroad.com" />
	<meta property="og:title" content="Ganjaroad" />
	<meta property="og:url" content="{{ Request::url() }}" />
	<meta property="og:image" content="{{ asset('assets/images/fb_logo.png') }}" />
	<meta property="og:description" content="{{ TemplateHelper::getSetting('homepage_meta_description') }}" />

	<link rel="icon" type="image/jpeg" href="/favicon.jpg" />
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<title>
		@section('title')

		@show
	</title>
	<!--global css starts-->
	<!-- FONTS	-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/Muli/stylesheet.css') }}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-reboot.css') }}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-grid.css') }}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-components.css') }}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.css') }}" media="all" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" media="all" /> 
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/global.css') }}" media="all" /> 
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" media="all" /> 
	
	<!--end of global css-->
	<!--page level css-->
	@yield('header_styles')
	<!--end of page level css-->
	@include('layouts/global_head')
</head>

<body>
    <!-- Header Start -->
	<header class="header">
		<?php /* old header
		<div class="container">
			@include('layouts/search')
			{!! TemplateHelper::generateAdd('top') !!}
		</div>
		@include('layouts/nav')
		*/ ?>
		@include('layouts/nav_new')
		<div class="container">
			{!! TemplateHelper::generateAdd('top') !!}
		</div>
	</header>
	
	@include('layouts/main_menu')
    
    @if (View::hasSection('breadcrumbs'))
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						@yield('breadcrumbs')
					</div>
				</div>
			</div>
		</div>
	@endif
	
    <!-- Content -->
	<div class="main" role="main">
		<div class="container">
			@include('notifications')
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-8 col-md-push-3 col-lg-push-2 matchHeight">
					@yield('content')
				</div>
				
				@section('leftcol')
					<div class="col-sm-6 col-md-3 col-lg-2 col-md-pull-6 col-lg-pull-8">
						<div class="main_sidebar_first matchHeight" role="complementary">
							@if (!View::hasSection('leftcol_content'))
								@include('layouts/leftcol')
							@else
								@yield('leftcol_content')
							@endif
						</div>
					</div>
				@show
				
				@section('rightcol')
					<div class="col-sm-6 col-md-3 col-lg-2">
						<div class="main_sidebar_secound matchHeight" role="complementary">
							@if (!View::hasSection('rightcol_content'))
								@include('layouts/rightcol')
							@else
								@yield('rightcol_content')
							@endif
						</div>
					</div>
				@show
				
			</div>
			{!! TemplateHelper::generateAdd('bottom') !!}
		</div>
	</div>

    <!-- Footer Section Start -->
	@include('layouts/footer')
	
    <!--global js starts-->
	<?php /*
	<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
	*/ ?>
	<script src="{{ asset('assets/js/vendor/jquery-1.10.2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/vendor/bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap-select/bootstrap-select.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/fns.js') }}"></script>	
	<script src="{{ asset('assets/js/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script>
	
	
	<!--global js end-->
	<!-- begin page level js -->
	@yield('footer_scripts')
	<!-- end page level js -->
</body>

</html>
