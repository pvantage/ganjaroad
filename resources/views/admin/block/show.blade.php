@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('block/title.blockdetail')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" href="{{ asset('assets/css/pages/blog.css') }}" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>{!! $block->title!!}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li> @lang('block/title.block')</li>
        <li class="active">@lang('block/title.blockdetail')</li>
    </ol>
</section>
<!--section ends-->
<section class="content">
    <!--main content-->
    <div class="row">
        <div class="col-sm-11 col-md-12 col-full-width-right">
            <div class="the-box no-border blog-detail-content">
                <p>
                    <span class="label label-danger square">{!! $block->created_at!!}</span>
                </p>
                {!! TemplateHelper::getDescription($block->content) !!}
            </div>
            <!-- /the.box .no-border --> </div>
        <!-- /.col-sm-9 --></div>
    <!--main content ends-->
</section>
@stop