@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('sales/title.reps_sales')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('sales/title.reps_sales')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('sales/title.sales')</li>
        <li class="active">@lang('sales/title.reps_sales')</li>
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
								@lang('sales/title.reps_sales')
							</h4>
						</div>

						<div class="col-xs-6 col-md-6"> 
							<div class="pull-right">
								<a href="javascript:void(0)" onclick="exportResult('export');" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-export"></i>@lang('sales/title.export')</a>
								<a href="javascript:void(0)" onclick="exportResult('print');" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-print"></i>@lang('sales/title.print')</a>
							</div>
						</div>
					</div>
				</div>
            </div>
            <br />
            <div class="panel-body">
                @include('admin.sales.print_sales', ['print' => false, 'users' => $users])
            </div>
        </div>
    </div>    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#sales_table').DataTable({
			responsive: true,
			'footerCallback': function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				// Total over all pages
				total = api
					.column( 4 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
				pageTotal = api
					.column( 4, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column( 4 ).footer() ).html(
					'$'+pageTotal +' ( $'+ total +' total)'
				);
			}
		});
    });
	
	function exportResult(type)
	{
		var searchIDs = $("#sales_table input:checkbox:checked").map(function(){
			return $(this).val();
		}).get();
		
		if(searchIDs) {
			$.ajax({
                type: 'GET', 
                url: '{!! route('sales.reps.print') !!}', 
                data: {ids: searchIDs, type: type}, 
                dataType: 'json',
                success: function (data) {
					if(data.msg) {
						showNotice(data.msg);
					} else {
						showNotice('@lang('sales/message.error.something_went_wrong')');
					}
                }
            }); 
		}
	}
	
	function showNotice(msg) {
		jQuery('#message .modal-header .modal-title').text("@lang('sales/title.download_report_title')");
		jQuery('#message .modal-body').html(msg);
		
		jQuery('#message').modal('show');
	}
</script>

<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="popup_title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">@lang('general.close')</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="sales_modal" tabindex="-1" role="dialog" aria-labelledby="sales_modal" aria-hidden="true">
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