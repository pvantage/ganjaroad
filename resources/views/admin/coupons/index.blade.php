@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('coupons/title.coupons')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('coupons/title.coupons')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('coupons/title.sales')</li>
        <li class="active">@lang('coupons/title.coupons')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<h4 class="panel-title pull-left">
								<i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
								@lang('coupons/title.coupons')
							</h4>
						</div>

						<div class="col-xs-6 col-md-6"> 
							 <div class="pull-right">
								<a href="{{ URL::to('admin/sales/coupons/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
							</div>
						</div>
					</div>
				</div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
							<th>@lang('coupons/table.id')</th>
							<th>@lang('coupons/table.code')</th>
							<th>@lang('coupons/table.uses_per_coupon')</th>
							<th>@lang('coupons/table.discount')</th>
                            <th>@lang('coupons/table.start_date')</th>
                            <th>@lang('coupons/table.end_date')</th>
                            <th>@lang('coupons/table.active')</th>
                            <th>@lang('coupons/table.times_used')</th>
                            <th>@lang('coupons/table.created_at')</th>
							<th>@lang('coupons/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#table').DataTable({
        responsive: true,
		processing: true,
		serverSide: true,
		order: [[ 0, 'desc' ]],
		ajax: '{!! route('coupons.datalist') !!}',
		columns: [
			{ data: 'id', name: 'id'},
			{ data: 'code', name: 'code'},
			{ data: 'uses_per_coupon', name: 'uses_per_coupon'},
			{ data: 'discount', name: 'discount'},
			{ data: 'published_from', name: 'published_from'},
			{ data: 'published_to', name: 'published_to', type: 'date'},
			{ data: 'active', name: 'active'},
			{ data: 'times_used', name: 'times_used'},
			{ data: 'created_at', name: 'created_at', type: 'date'},
			{ data: 'actions', name: 'actions', orderable: false, searchable: false}
		]
    });
	table.on( 'draw', function (){
		$('.livicon').each(function(){
			$(this).updateLivicon();
		});
	});
	table.on( 'responsive-display', function ( e, datatable, row, showHide, update ) {
		$('.livicon').each(function(){
			$(this).updateLivicon();
		});
	});
});
</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
  </div>
</div>
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script>
@stop