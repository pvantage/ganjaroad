@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('ads/title.edit')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/images-upload.css') }}">
	
    <link href="{{ asset('assets/vendors/summernote/summernote.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
	
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop



{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('ads/title.edit')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('ads/title.ad')</a>
        </li>
        <li class="active">@lang('ads/title.edit')</li>
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
			{!! Form::model($ads, array('url' => URL::to('admin/ads/' . $ads->id.'/edit'), 'id' => 'ads', 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
							{!! Form::label('title', trans('ads/form.title')) !!}
                            {!! Form::text('title', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('ads/form.title'))) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('company', trans('ads/form.select-company')) !!}
                            {!! Form::select('company', $companies, null, array('class' => 'form-control select2', 'placeholder'=>trans('ads/form.select-company'))) !!}
                        </div>

						<div class="form-group">
							{!! Form::label('phone', trans('ads/form.phone')) !!}
							{!! Form::text('phone', null, array('class' => 'form-control input-lg', 'placeholder'=> trans('ads/form.phone'))) !!}
						</div>
						
						<div class="form-group">
							{!! Form::label('url', trans('ads/form.url')) !!}
                            {!! Form::text('url', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('ads/form.url'))) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('position_id', trans('ads/form.position')) !!}
                            {!! Form::select('position_id',$adspositions ,null, array('class' => 'form-control select2', 'placeholder'=>trans('ads/form.select-position'))) !!}
                        </div>
						
						
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<label>@lang('ads/form.image')</label>
								</div>
								<div class="col-md-12">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 150px;">
											@if($ads->image)
												<img src="{{ asset('uploads/ads/'.$ads->image) }}" class="img-responsive" />
											@else
											@endif
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
										<div>
											<span class="btn btn-primary btn-file">
												<span class="fileinput-new">@lang('front/general.select_image')</span>
												<span class="fileinput-exists">@lang('front/general.change')</span>
												<input type="file" name="image" id="image" />
											</span>
											<span class="btn btn-primary fileinput-exists" data-dismiss="fileinput">@lang('front/general.remove')</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
						<div class="form-group">
							<a data-toggle="button" class="btn btn-primary btn-block" href="#">@lang('ads/form.views'): {!! $ads->views !!}</a>
							<a data-toggle="button" class="btn btn-primary btn-block btn-green" href="#">@lang('ads/form.clicks'): {!! $ads->clicks !!}</a>
						</div>
						
						<div class="form-group">
							{!! $ads->getAdminAgreementTxt() !!}
						</div>
						
						@if($ads->canBeRefunded())
							<div class="form-group">
								<a href="#" data-toggle="modal"
									data-target="#refund" title="@lang('ads/form.give_refund')" class="btn btn-danger">
									@lang('ads/form.give_refund')
								</a>
							</div>
						@endif
						
						<div class="form-group">
							{!! Form::label('published', trans('ads/form.published')) !!}
                            {!! Form::YesNo('published', $ads->published) !!}
						</div>
						
						<div class="form-group">
                            {!! Form::label('paid', trans('ads/form.paid')) !!}
							{!! Form::YesNo('paid', $ads->paid) !!}
                        </div>
						
						<div class="form-group">
                            {!! Form::label('approved', trans('ads/form.approved')) !!}
							{!! Form::YesNo('approved', $ads->approved) !!}
							
							<div class="form-group">
								@lang('ads/form.approved_comment')
							</div>
                        </div>
						
						<div class="form-group">
							{!! Form::label('published_from', trans('ads/form.published_from')) !!}
							{!! Form::DateTimeInput('published_from')!!}
						</div>						
						
						<div class="form-group">
							{!! Form::label('published_to', trans('ads/form.published_to')) !!}
							{!! Form::DateTimeInput('published_to')!!}
						</div>
						
						<?php /*
						<div class="form-group">
							{!! Form::label('recurring', trans('ads/form.recurring')) !!}
							{!! Form::YesNo('recurring', $ads->recurring) !!}
						</div>
						*/ ?>
						
						<div class="form-group">
                            <button type="submit" class="btn btn-success">@lang('ads/form.update')</button>
                            <a href="{{ URL::to('admin/ads') }}" class="btn btn-danger">@lang('ads/form.discard')</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                <!-- /.row -->
			{!! Form::close() !!}
			
			@include('admin/layouts/item_transaction_history', ['item' => $ads])
			
        </div>
    </div>
    <!--main content ends-->
</section>

@if($ads->canBeRefunded())
	<div class="modal fade" id="refund" tabindex="-2" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="refund_label">@lang('ads/form.give_refund')</h4>
				</div>
				<div class="modal-body">
					{!! Form::open(array('url' => route('refund', ['type' => 'ads', 'item_id' => $ads->id, 'transaction_id' => $last_transaction->payment->transaction_id]), 'method' => 'post', 'class' => 'bf')) !!}
						<div class="form-group">
							{!! Form::text('amount', TemplateHelper::getItemMaxRefund($last_transaction->payment->transaction_id, $ads), array('id' => 'refund_amount', 'placeholder'=> trans('ads/form.amount_to_refund'))) !!}
						</div>
						<button type="submit" class="btn btn-success">@lang('ads/form.refund')</button>
						<a href="#" class="btn btn-danger" data-dismiss="modal">@lang('ads/form.discard')</a>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endif

@stop
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit ads-->
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js') }}" ></script>
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/add_newblog.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	@if($ads->canBeRefunded())
		jQuery("#refund_amount").TouchSpin({
			min: 0,
			max: {{ TemplateHelper::getItemMaxRefund($last_transaction->payment->transaction_id, $ads) }},
			step: 0.1,
			decimals: 2,
			boostat: 5,
			maxboostedstep: 10,
			prefix: '{!! TemplateHelper::getCurrencySymbol() !!}'
		});
	@endif
	
	jQuery('.yesno-value').each(function() {
		jQuery(this).bootstrapSwitch('state');
	});
	
	jQuery('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = jQuery(this).attr('data-config');

		jQuery('input[name="'+id+'"]').val(+state);
	});
});
</script>
{!! TemplateHelper::includeDateScript() !!}
@stop