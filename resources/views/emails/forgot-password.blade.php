@extends('emails/layouts/default')

@section('content')
<p>@lang('emails/general.forgot.hello') {!! $user->first_name !!} {!! $user->last_name !!},</p>

<p>@lang('emails/general.forgot.update_pass'):</p>

<p><a href="{!! $forgotPasswordUrl !!}">{!! $forgotPasswordUrl !!}</a></p>

<p>@lang('emails/general.forgot.regards'),</p>

<p>@lang('general.site_name')</p>
@stop
