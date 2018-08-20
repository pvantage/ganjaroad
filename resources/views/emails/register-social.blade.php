@extends('emails/layouts/default')

@section('content')
@if($first_name)
	<p>@lang('emails/general.register.hello') {!! $first_name !!},</p>
@endif

<p>@lang('emails/general.register.welcome')</p>
<p>@lang('emails/general.register.account_created')</p>
@if($first_name)
	<p>@lang('emails/general.register.account_newpassword')</p>
	<p>{!! $password !!}</p>
@endif

<p>@lang('emails/general.register.body')</p>

<p>@lang('emails/general.register.regards'),</p>

<p>@lang('emails/general.register.rebards_name')</p>
@stop
