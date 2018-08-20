@extends('admin/layouts/default')

{{-- Coupons title --}}
@section('title')
    @lang('coupons/title.edit-coupon') :: @parent
@stop

{{-- coupons level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">	
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
    <!--end of coupons level css-->
@stop


{{-- Coupons content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('coupons/title.edit-coupon')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('coupons/title.coupon')</a>
        </li>
        <li class="active">@lang('coupons/title.edit-coupon')</li>
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
			{!! Form::model($coupons, array('url' => URL::to('admin/sales/coupons/' . $coupons->id.'/edit'), 'id' => 'coupons', 'method' => 'post', 'class' => 'bf')) !!}
                 <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
							{!! Form::label('code', trans('coupons/form.code')) !!}
                            {!! Form::text('code', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('coupons/form.code'))) !!}
                        </div>
						<div class="form-group">
                            {!! Form::label('type', trans('coupons/form.type')) !!}
                            {!! Form::select('type', $types ,null, array('class' => 'form-control select2', 'placeholder'=>trans('coupons/form.select-type'))) !!}
                        </div>
						<div class="form-group">
							{!! Form::label('discount', trans('coupons/form.discount')) !!}
                            {!! Form::text('discount', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('coupons/form.discount'))) !!}
                        </div>
						<div class="form-group">
							{!! Form::label('max_amount', trans('coupons/form.max_amount')) !!}
                            {!! Form::text('max_amount', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('coupons/form.max_amount'))) !!}
                        </div>
						<div class="form-group">
							{!! Form::label('uses_per_coupon', trans('coupons/form.uses_per_coupon')) !!}
                            {!! Form::text('uses_per_coupon', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('coupons/form.uses_per_coupon'))) !!}
                        </div>
						<div class="form-group">
							{!! Form::label('uses_per_user', trans('coupons/form.uses_per_user')) !!}
                            {!! Form::text('uses_per_user', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('coupons/form.uses_per_user'))) !!}
                        </div>
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
						<div class="form-group">
							<a data-toggle="button" class="btn btn-primary btn-block" href="#">
                                @lang('coupons/form.times_used'): {!! $coupons->times_used !!} ({!! $ad_coupon->count() !!})
                            </a>

                            @if($ad_coupon->count())
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Id
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($ad_coupon as $value)
                                            <tr>
                                                <td>
                                                    {{ $value->ad->id }}
                                                </td>
                                                <td>
                                                    {{ $value->ad->title }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
						</div>
						
                        <div class="form-group">
							{!! Form::label('active', trans('coupons/form.active')) !!}
                            {!! Form::YesNo('active') !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('published_from', trans('coupons/form.start_date')) !!}
							{!! Form::DateTimeInput('published_from')!!}
						</div>
                      
						<div class="form-group">
							{!! Form::label('published_to', trans('coupons/form.end_date')) !!}
							{!! Form::DateTimeInput('published_to')!!}
						</div>
						
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('coupons/form.save')</button>
                            <a href="{!! URL::to('admin/sales/coupons') !!}"
                               class="btn btn-danger">@lang('coupons/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 -->
				</div>
				<input type="hidden" id="id" name="id" class="form-control" value="{!! $coupons->id !!}" />
                {!! Form::close() !!}
        </div>
    </div>
    <!--main content ends-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit page-->
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.yesno-value').each(function() {
		$(this).bootstrapSwitch('state');
	});
	
	$('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = $(this).attr('data-config');

		$('input[name="'+id+'"]').val(+state);
	});
});
</script>
{!! TemplateHelper::includeDateScript() !!}
@stop