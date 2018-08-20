@extends('admin/layouts/default')

{{-- Web site Title --}}

@section('title')
    @lang('classifiedschema/title.create') :: @parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link href="{{ asset('assets/css/tier.css') }}" rel="stylesheet" />
    <!--end of page level css-->
@stop

{{-- Content --}}

@section('content')
<section class="content-header">
    <h1>
        @lang('classifiedschema/title.create')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i> Dashboard
            </a>
        </li>
        <li>@lang('classifiedschema/title.classifiedschema')</li>
        <li class="active">
            @lang('classifiedschema/title.create')
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('classifiedschema/title.create')
                    </h4>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('admin/classifiedschema/create'), 'method' => 'post', 'class' => 'form-horizontal', 'files'=> true)) !!}
                    <div class="form-group {{ $errors->first('title', 'has-error') }}">
                        <label for="title" class="col-sm-2 control-label">
                            @lang('classifiedschema/form.title')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('title', null, array('class' => 'form-control', 'placeholder'=>trans('classifiedschema/form.title'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('title', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					
					<?php /*
					<div class="form-group {{ $errors->first('amount', 'has-error') }}">
                        <label for="amount" class="col-sm-2 control-label">
                            @lang('classifiedschema/form.amount')
                        </label>
                        <div class="col-sm-5">
                            {!! Form::text('amount', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=> trans('classifiedschema/form.amount_placeholder'))) !!}
                        </div>
                        <div class="col-sm-4">
                            {!! $errors->first('amount', '<span class="help-block">:message</span> ') !!}
                        </div>
                    </div>
					*/ ?>
					<input type="hidden" name="amount" value="0" />
					
					<div class="tier-prices">
						<div class="col-sm-2"></div>
						<div class="col-sm-10">
							<h3>@lang('tier/form.prices')</h3>
						</div>
						<div class="tier-wrapper">
							<div class="hidden tier-pattern">
								<div class="form-group tier-item">
									<div class="col-md-2"></div>
									<div class="col-md-2">
										<label class="control-label">
											@lang('tier/form.from')
										</label>
										{!! Form::text('tier[0][from]', null, array('class' => 'form-control input-lg tier-amount', 'placeholder'=> trans('tier/form.from'))) !!}
									</div>
									<div class="col-md-2">
										<label class="control-label">
											@lang('tier/form.to')
										</label>
										{!! Form::text('tier[0][to]', null, array('class' => 'form-control input-lg tier-amount', 'placeholder'=> trans('tier/form.to'))) !!}
									</div>
									<div class="col-md-2">
										<label class="control-label">
											@lang('tier/form.priority_label')
										</label>
										{!! Form::text('tier[0][priority]', null, array('class' => 'form-control input-lg tier-amount', 'placeholder'=> trans('tier/form.priority'))) !!}
									</div>
									<div class="col-md-2">
										<label class="control-label">
											@lang('tier/form.price')
										</label>
										{!! Form::text('tier[0][price]', null, array('class' => 'form-control input-lg tier-price', 'placeholder'=> trans('tier/form.price'))) !!}
									</div>
									<div class="col-md-2">
										<label class="control-label">&nbsp;</label>
										<div class="actions">
											<a href="javascript:void(0)" class="remove-tier">
												@lang('tier/form.remove')
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="tier-items"></div>
						</div>
					
						<div class="form-group tier-add">
							<div class="col-md-10 align-right">
								<div class="actions">
									<a href="javascript:void(0)" class="add-tier">
										<i class="fa fa-plus-circle" aria-hidden="true" title="@lang('tier/form.add')"></i> @lang('tier/form.add_row')
									</a>
								</div>
							</div>
							<div class="col-md-2"></div>
						</div>
					</div>
					
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <a class="btn btn-danger" href="{{ URL::to('admin/classifiedschema/') }}">
                                @lang('button.cancel')
                            </a>
                            <button type="submit" class="btn btn-success">
                                @lang('button.save')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js') }}" ></script>

<script type="text/javascript">
$("document").ready(function() {
	render();
	
	var tier = $('.tier-pattern').clone().removeClass('hidden').html();
	$('.tier-pattern').remove();
	$('.add-tier').on('click', function(){
		$('.tier-items').append(tier);
		render();
		renumber_blocks();
	});
	
	$(document).on('click', '.remove-tier', function(){
		$(this).closest('.form-group').remove();
		renumber_blocks();
	});
});

function render() {
	$("<?php /*input[name='amount'], */ ?>.tier-items .tier-price").TouchSpin({
		min: 0,
		max: 10000,
		step: 0.1,
		decimals: 2,
		boostat: 5,
		maxboostedstep: 10,
		prefix: '{!! TemplateHelper::getCurrencySymbol() !!}'
	});
	
	$(".tier-items .tier-amount").TouchSpin({
		min: 0,
		max: 10000,
		step: 1,
		boostat: 1,
	});
}

function renumber_blocks() {
    $(".tier-items .tier-item").each(function(index) {
        var prefix = "tier[" + index + "]";
        $(this).find("input").each(function() {
           this.name = this.name.replace(/tier\[\d+\]/, prefix);   
        });
    });
}
</script>
@stop