@extends('layouts/default')

@section('meta')
	<meta name="title" content="{{ $page->title }}">
	<meta name="description" content="{{ $page->meta_description }}">
	<meta name="keywords" content="{{ $page->meta_keywords }}">
@parent
@stop

{{-- Page title --}}
@section('title')
    {{ $page->title }}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/vendors/jquery-easy-ticker/jquery.easy-ticker.css') }}" rel="stylesheet" type="text/css">
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">{{ $page->title }}</li>
	</ol>               
@stop

@if(isset($leftcol) && $leftcol)
	@section('leftcol')
		@section('leftcol_content')
			@include('layouts/user_leftcol')
		@stop
		@parent
	@stop
@endif

@section('content')
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>{{ $page->title }}</h1>
		</div>
		<hr>
		<div class="content panel-body">
            {!! TemplateHelper::getDescription($page->content) !!}
		</div>
	</div>
@stop

@section('footer_scripts')

@stop