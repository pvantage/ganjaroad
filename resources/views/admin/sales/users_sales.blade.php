@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('sales/title.users_sales')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('sales/title.users_sales')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('sales/title.sales')</li>
        <li class="active">@lang('sales/title.users_sales')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
				<div class="container-fluid">
					<form action="{{ route('sales.users') }}">
						<div class="row">
							<div class="col-xs-6 col-md-6">
								<h4 class="panel-title pull-left">
									<i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
									@lang('sales/title.users_sales')
								</h4>
							</div>

							<div class="col-xs-6 col-md-6">
								<div class="pull-right">
									<a href="javascript:void(0)" onclick="exportResult('export');" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-export"></i>@lang('sales/title.export')</a>
									<a href="javascript:void(0)" onclick="exportResult('print');" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-print"></i>@lang('sales/title.print')</a>
									<a href="javascript:void(0)" onclick="$(this).closest('form').submit()" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-saved"></i>@lang('sales/title.apply_filters')</a>
									<a href="{{ route('sales.users') }}" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i>@lang('sales/title.remove_filters')</a>
								</div>
							</div>
						</div>
						<div class="row header-filters">
							<div class="pull-left">
									{!! Form::label('filter_type', trans('sales/title.filter_type'), array('class' => 'control-label')) !!}
									{!! Form::select('filter_type', TemplateHelper::getAdsTypes(true), $filter_type, ['class' => 'select form-control', 'id' => 'filter_type']) !!}
								</div>
							<div class="pull-right">
								<div class="pull-left range-filter">
									{!! Form::label('filter_range', trans('sales/title.filter_range'), array('class' => 'control-label')) !!}
									{!! Form::select('filter_range', $ranges, $filter_range, ['class' => 'select form-control', 'id' => 'filter_range']) !!}
								</div>
								<div class="pull-left custom-range" @if($filter_range != 'custom') style="display: none" @endif>
									{!! Form::label('date_from', trans('sales/title.filter_from'), array('class' => 'control-label')) !!}
									{!! Form::DateTimeInput('date_from', $filter_sdate, array('class' => 'from'))!!}
									{!! Form::label('date_to', trans('sales/title.filter_to'), array('class' => 'control-label')) !!}
									{!! Form::DateTimeInput('date_to', $filter_edate, array('class' => 'to'))!!}
								</div>
							</div>
						</div>
					</form>
				</div>
            </div>
            <br />
            <div class="panel-body">
                @include('admin.sales.print_users_sales', ['print' => false, 'users' => $users])
            </div>
        </div>
    </div>    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
{!! TemplateHelper::includeDateScript() !!}
<script type="text/javascript">
	 $(document).ready(function() {
		$('#filter_range').on('click', function(){
			if($(this).val() == 'custom') {
				$('.custom-range').show();
			} else $('.custom-range').hide();
		});
	
		var table = $('#sales_table').DataTable({
			responsive: true,
			processing: true,
			serverSide: true,
			order: [[ 8, 'desc' ]],
			ajax: {
				type: 'GET',
				url: '{!! route('sales.users.datalist') !!}',
				data: {
				   filter_type: '{{ $filter_type }}',
				   filter_range: '{{ $filter_range }}',
				   date_from: '{{ $filter_sdate }}',
				   date_to: '{{ $filter_edate }}'
				},
			},
			columns: [
				{ data: 'checkobx', name: 'checkobx', orderable: false, searchable: false},
				{ data: 'fullname', name: 'fullname'},
				{ data: 'email', name: 'email'},
				{ data: 'location', name: 'location', orderable: false},
				{ data: 'ads_count', name: 'ads_count'},
				{ data: 'classified_count', name: 'classified_count'},
				{ data: 'nearme_count', name: 'nearme_count'},
				{ data: 'amount', name: 'amount'},
				{ data: 'latest_payment_created_at', name: 'payment_created_at' },
				{ data: 'city', name: 'city'},
				{ data: 'state', name: 'state'},
				{ data: 'country', name: 'country'},
				{ data: 'actions', name: 'actions', orderable: false, searchable: false}
			],
			search: {
				'regex': true
			}
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
	function exportResult(type)
	{
		var searchIDs = $("#sales_table input:checkbox:checked").map(function(){
			return $(this).val();
		}).get();
		
		if(searchIDs) {
			$.ajax({
                type: 'GET', 
                url: '{!! route('sales.users.print') !!}', 
                data: {ids: searchIDs, type: type, filter_type: '{{ $filter_type }}', filter_range: '{{ $filter_range }}', date_from: '{{ $filter_sdate }}', date_to: '{{ $filter_edate }}' }, 
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