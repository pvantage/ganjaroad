@extends('emails/layouts/default')

@section('content')
	<p>@lang('emails/general.ad-unpublished.body', array('title' => $data['item']->title))</p>
	
	<p>Greenest regards,<br/>
	The ganjaroad.com Team<br/>
	(888) 904-WEED</p>
@stop
