@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{$blog->title}}
@parent
@stop

@section('meta')
	<meta name="title" content="{{ $blog->title }}">
	<meta name="description" content="{{ $blog->meta_description }}">
	<meta name="keywords" content="{{ $blog->meta_keywords }}">
	@if($blog->canonical)
		<link rel="canonical" href="{{ $blog->canonical }}" /> 
	@endif
@parent
@stop


{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/blog.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ TemplateHelper::blogCategoryLink($blog->blog_category_id)}}">{{ $blog->category_title() }}</a></li>
		<li class="active">{{ $blog->title }}</li>
	</ol>               
@stop

@section('leftcol')
	@section('leftcol_content')
		<div class="block">
			<h3 class="block-title">@lang('front/general.blog_categories')</h3>
		</div>
		<div class="content">
			{{--*/ $categories = TemplateHelper::getBlogCategories() /*--}}
			<dl>
				@forelse ($categories as $category)
					<dt><dd><a href="{{ TemplateHelper::blogCategoryLink($category->id, $category->slug) }}">{{ $category->title }}</a></dd></dt>
				@empty
				
				@endforelse
			</dl>
		</div>
	@stop
	@parent
@stop

@section('rightcol')
	@section('rightcol_content')
		<div class="block">
			{!! TemplateHelper::generateAdd('right') !!}
		</div>
	@stop
	@parent
@stop


{{-- Page content --}}
@section('content')
    <div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>{{$blog->title}}</h1>
		</div>
		<hr>
        <div class="content">
            <div class="col-sm-12 col-md-12">
				<p class="additional-post-wrap">
					<span class="additional-post">
						<i class="livicon" data-name="clock" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i><a href="#"> {{$blog->created_at->diffForHumans()}} </a>
					</span>
					@if(TemplateHelper::enableBlogComments($blog->category->id))
						<span class="additional-post">
							<i class="livicon" data-name="comment" data-size="13" data-loop="true" data-c="#5bc0de" data-hc="#5bc0de"></i><a href="#"> {{$blog->comments->count()}} comments</a>
						</span>
					@endif
				</p>
				@if($blog->image)
					<img src="{{ asset('uploads/blog/'.$blog->image)  }}" class="img-responsive" alt="Image">
				@endif
				{!! TemplateHelper::getDescription($blog->content) !!}
				<p>
					@if($blog->tags->count())
						<strong>@lang('front/general.tags'): </strong>
						@forelse($blog->tags as $tag)
							<a href="{!! TemplateHelper::blogTagLink($tag) !!}">{{ $tag }}</a>,
						@empty
							@lang('front/general.no_tags')
						@endforelse
					@endif
				</p>
				
				@if(TemplateHelper::enableBlogComments($blog->category->id))
					<h3 class="comments">{{$blog->comments->count()}} @lang('front/general.comments')</h3><br />
					<ul class="media-list">
						@foreach($blog->comments as $comment)
						<li class="media">
							<div class="media-body">
								<h4 class="media-heading"><i>{{$comment->name}}</i></h4>
								<p>{{$comment->comment}}</p>
								<p class="text-danger">
									<small> {!! $comment->created_at!!}</small>
								</p>
							</div>
						</li>
						@endforeach
					</ul>

				
					<h3>@lang('front/general.leave_a_comment')</h3>
					{!! Form::open(array('url' => URL::to('blogitem/'.$blog->id.'/comment'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}

					<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
						{!! Form::text('name', null, array('class' => 'form-control input-lg','required' => 'required', 'placeholder'=>'Your name')) !!}
						<span class="help-block">{{ $errors->first('name', ':message') }}</span>
					</div>
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
						{!! Form::text('email', null, array('class' => 'form-control input-lg','required' => 'required', 'placeholder'=>'Your email')) !!}
						<span class="help-block">{{ $errors->first('email', ':message') }}</span>
					</div>
					<div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">
						{!! Form::text('website', null, array('class' => 'form-control input-lg', 'placeholder'=>'Your website')) !!}
						<span class="help-block">{{ $errors->first('website', ':message') }}</span>
					</div>
					<div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
						{!! Form::textarea('comment', null, array('class' => 'form-control input-lg no-resize','required' => 'required', 'style'=>'height: 200px', 'placeholder'=>'Your comment')) !!}
						<span class="help-block">{{ $errors->first('comment', ':message') }}</span>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-md"><i class="fa fa-comment"></i>
							@lang('front/general.submit')
						</button>
					</div>
					{!! Form::close() !!}
				@endif
            </div>
        </div>
    </div>
@stop
