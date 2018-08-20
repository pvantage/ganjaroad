@extends('emails/layouts/default')

@section('content')
	<h1>Attention {!! $data['user_name'] !!},</h1>
	<p>@lang('classified/emails.classifieds-reminder-body', array('days' => $data['days'], 'title' => $data['title']))</p>
	
	<p>Greenest regards,<br/>
	The ganjaroad.com Team<br/>
	(888) 904-WEED</p>
@stop
