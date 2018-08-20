@extends('emails/layouts/default')

@section('content')
	<p>Your request to add {{ $type }} entries at ganjaroad.com has been approved. <a href="{{ URL::to('login' ) }}">Please click here</a> to login and add your entry.</p>
@stop
