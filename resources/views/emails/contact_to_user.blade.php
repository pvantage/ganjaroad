@extends('emails/layouts/default')

@section('content')
    <p>Hello {{ $data['name'] }},</p>
    <p>We have received your contact mail.</p>
	<p>Data you sent:</p>

	<p>Name: {{ $data['name'] }}</p>

    <p>Email: {{ $data['email'] }}</p>

    <p>Message: {{ $data['message'] }}  </p>
@stop
