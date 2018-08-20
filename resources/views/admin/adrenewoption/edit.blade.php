@extends('admin/layouts/default')

@section('title')
    @lang('adrenewoption/title.ad-renew-option') :: @parent
@stop

@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>@lang('adrenewoption/title.edit-ad-renew-option')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                    @lang('general.home')
                </a>
            </li>
            <li>
                <a href="#">@lang('adrenewoption/title.ad-renew-option')</a>
            </li>
            <li class="active">@lang('adrenewoption/title.edit-ad-renew-option')</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content paddingleft_right15">
        <!--main content-->
        <div class="row">
            <div class="the-box no-border">
                <div class="has-error">
                    @if($errors->has())
                        @foreach ($errors->all() as $error)
                            <span class="help-block">{{ $error }}</span>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
        {!! Form::model($adrenewoption, array('url' => URL::to('admin/adrenewoption/' . $adrenewoption->id.'/edit'), 'method' => 'post', 'class' => 'bf form-horizontal')) !!}
        <div class="row">
            <div class="container">
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group">
                        {!! Form::label('renewal_period_name', trans('adrenewoption/form.renewal_period_name')) !!}
                        {!! Form::text('renewal_period_name', null, ['placeholder' => 'eg. 2 Weeks/6 Months', 'class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('renewal_time', trans('adrenewoption/form.renewal_time')) !!}
                        {!! Form::number('renewal_time', null, ['placeholder' => 'eg. 1', 'class' => 'form-control', 'required' => 'required', 'min' => '1', 'max' => '35']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('renewal_time_period', trans('adrenewoption/form.renewal_time_period')) !!}
                        {!! Form::select('renewal_time_period', ['d' => 'Days', 'w' => 'Weeks', 'm' => 'Months', 'y' => 'Years'], null, ['placeholder' => 'Pick up renewal time period', 'class' => 'form-control select2', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('renewal_type', trans('adrenewoption/form.renewal_type')) !!}
                        {!! Form::select('renewal_type', ['nearme' => 'Provider Ad', 'classified' => 'Classified', 'banner_ad' => 'Banner Ad'], null, ['placeholder' => 'Pick up renewal type', 'class' => 'form-control select2', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('renewal_discount', trans('adrenewoption/form.renewal_discount')) !!}
                        {!! Form::number('renewal_discount', null, ['placeholder' => 'eg. 15', 'class' => 'form-control', 'required' => 'required', 'min' => '0', 'max' => '100']) !!}
                    </div>
                </div>
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">@lang('page/form.publish')</button>
                        <a href="{!! URL::to('admin/adrenewoption') !!}"
                           class="btn btn-danger">@lang('page/form.discard')</a>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    <!--main content ends-->
    </section>
@stop


@section('footer_scripts')

@stop
