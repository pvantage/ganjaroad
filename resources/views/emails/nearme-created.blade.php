@extends('emails/layouts/default')

@section('content')
	<h2>@lang('emails/general.nearme.created')</h2>

    <p><a href="{{ URL::to('admin/nearme/' . $data['id'] . '/edit' ) }}">@lang('emails/general.nearme.created_title'): {{ $data['title'] }}</a></p>
    <p>@lang('emails/general.nearme.created_category'): {{ $category['title'] }}</p>

@stop
