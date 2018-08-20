@extends('emails/layouts/default')

@section('content')
@if($user->first_name)
	<p>@lang('emails/general.register.hello') {!! $user->first_name !!},</p>
@endif

<p>@lang('emails/general.register.welcome')</p>
<p>@lang('emails/general.register.activate')</p>
<p><a href="{!! $activationUrl !!}">{!! $activationUrl !!}</a></p>
<p>@lang('emails/general.register.body')</p>

<p>@lang('emails/general.register.regards'),<br>

@lang('emails/general.register.rebards_name')</p>
<p><a href="http://www.ganjaroad.com/">www.Ganjaroad.com</a><br>
(formerly Craigsweed.com)<br>
<a href="mailto:info@ganjaroad.com">info@ganjaroad.com</a><br>
(888) 904-WEED (9333) -toll free</p>
@stop
