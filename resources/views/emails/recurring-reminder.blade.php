@extends('emails/layouts/default')

@section('content')
	<h1>@lang('emails/general.recurring-expire.hello')</h1>
	<p>@lang('emails/general.recurring-expire.body', array('login_link' => $data['login_link'], 'type' => $data['type'], 'title' => $data['title']))</p>
	
	<p>Greenest regards,<br/>
	The ganjaroad.com Team<br/>
	(888) 904-WEED</p>
@stop
