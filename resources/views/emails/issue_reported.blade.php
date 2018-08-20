@extends('emails/layouts/default')

@section('content')
	<h2>@lang('emails/general.issue-reported.title')</h2>
	
    <p>@lang('emails/general.issue-reported.user'): {{ $data['user'] }}</p>
    <p>@lang('emails/general.issue-reported.email'): {{ $data['email'] }}</p>
    <p>@lang('emails/general.issue-reported.nature'): {{ $data['nature'] }}</p>
    <p>@lang('emails/general.issue-reported.comment'): {{ $data['comment'] }}</p>
	
	@if($images)
		<p>@lang('emails/general.issue-reported.images')</p>
		@foreach($images as $key => $image)
			<p>
				<img src="{{ $image }}" alt="{{ $key }}" />
			</p>
		@endforeach
	@endif
@stop
