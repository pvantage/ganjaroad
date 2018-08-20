<!DOCTYPE html>
<html>
<head>
	<title>
		@lang('front/review.agreement_successfully_signed')
	</title>
	
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
</head>

<body>
	<div class="modal-header">
		<h4 class="modal-title" id="agree_modal_title">@lang('general.notice')</h4>
	</div>
	<div class="modal-body">
		<p>@lang('front/review.agreement_successfully_signed')</p>
		<p>@lang('front/review.please_reload_page_and_proceed_to_checkout')</p>
	</div>

	<script src="{{ asset('assets/js/vendor/jquery-1.10.2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/vendor/bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap-select/bootstrap-select.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/fns.js') }}"></script>	
	<script src="{{ asset('assets/js/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script>
</body>
</html>