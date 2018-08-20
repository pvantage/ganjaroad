@if ($errors->any())
	<div class="alert alert-danger alert-dismissable margin5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>@lang('front/general.error'):</strong> @lang('front/general.error_msg')
	</div>
@endif

@if ($message = Session::get('success'))
	<div class="alert alert-success alert-dismissable margin5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>@lang('front/general.success'):</strong> {!! $message !!}
	</div>
	{!! Session::forget('success') !!}
@endif

@if ($message = Session::get('error'))
	<div class="alert alert-danger alert-dismissable margin5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>@lang('front/general.error'):</strong> {!! $message !!}
	</div>
	{!! Session::forget('error') !!}
@endif

@if ($message = Session::get('warning'))
	<div class="alert alert-warning alert-dismissable margin5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>@lang('front/general.warning'):</strong> {!! $message !!}
	</div>
	{!! Session::forget('warning') !!}
@endif

@if ($message = Session::get('info'))
	<div class="alert alert-info alert-dismissable margin5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>@lang('front/general.info'):</strong> {!! $message !!}
	</div>
	{!! Session::forget('info') !!}
@endif

@if ($message = Session::get('notice'))
	<div class="alert alert-info alert-dismissable margin5">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>@lang('front/general.notice'):</strong> {!! $message !!}
	</div>
	{!! Session::forget('notice') !!}
@endif

@if ($message = Session::get('bad_word_warning'))
	<div class="alert alert-danger margin5">
		<strong>@lang('front/general.warning'): {!! $message !!}</strong>
	</div>
	{!! Session::forget('bad_word_warning') !!}
@endif

@if ($message = Session::get('important_message'))
	<div class="alert alert-danger margin5">
		{!! $message !!}
	</div>
	{!! Session::forget('important_message') !!}
@endif
