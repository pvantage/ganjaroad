@extends('emails/layouts/default')

@section('content')
	@if(isset($data['user_email']) && $data['user_email'])
		<h2>@lang('emails/general.request-access.header_user')</h2>
		<p style="background-color: red">@lang('emails/general.request-access.header_notice')</p>
	@else
		<h2>@lang('emails/general.request-access.header')</h2>
	@endif
	
    <p>@lang('emails/general.form_data'):</p>

	@if(!isset($data['user_email']))
		<p>@lang('emails/general.request-access.user'): {{ $user_data['first_name'] . ' ' . $user_data['last_name'] }}</p>
	@endif
    <p>@lang('emails/general.request-access.type'): {{ $data['type'] }}</p>
    <p>@lang('emails/general.request-access.business'): {{ $data['business'] }}</p>
    <p>@lang('emails/general.request-access.contact'): {{ $data['contact'] }}</p>
    <p>@lang('emails/general.request-access.email'): {{ $data['email'] }}</p>
    <p>@lang('emails/general.request-access.phone'): {{ $data['phone'] }}</p>
    <p>@lang('emails/general.request-access.address'): {{ $data['address'] }}</p>

@stop
