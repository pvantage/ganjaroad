@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('sales/title.info')
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
@stop

@section('content')
<section class="content-header">
    <h1>@lang('sales/title.sales')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('sales/title.sales')</li>
        <li class="active">@lang('sales/title.sales_info')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<form action="{{ route('sales/users/info', $user->id) }}">
						<div class="row">
							<h4 class="panel-title pull-left"> <i class="livicon" data-name="comment" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
								@lang('sales/title.info')
							</h4>
							<div class="pull-right">
								<a href="javascript:void(0)" onclick="exportResult('export');" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-export"></i>@lang('sales/title.export')</a>
								<a href="javascript:void(0)" onclick="exportResult('print');" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-print"></i>@lang('sales/title.print')</a>
								<a href="javascript:void(0)" onclick="$(this).closest('form').submit()" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-saved"></i>@lang('sales/title.apply_filters')</a>
								<a href="{{ route('sales/users/info', $user->id) }}" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i>@lang('sales/title.remove_filters')</a>
							</div>
						</div>
						<div class="row header-filters">
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
                <br />
                <div class="panel-body">
					@include('admin.sales.print_users_info', ['print' => false, 'user' => $user, 'transactions' => $transactions])
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

@stop

@section('footer_scripts')
{{-- Body Bottom confirm modal --}}
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

<script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

{!! TemplateHelper::includeDateScript() !!}
<script type="text/javascript">
$(document).ready(function() {
	$('#transactions').DataTable({
		responsive: true,
		order: [[ 5, 'desc' ]],
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
                '$'+pageTotal +' ( $'+ parseFloat(total).toFixed(2) +' total)'
            );
        }
	});
	
	$('#filter_range').on('click', function(){
		if($(this).val() == 'custom') {
			$('.custom-range').show();
		} else $('.custom-range').hide();
	});
});

function exportResult(type)
{
	$.ajax({
		type: 'GET', 
		url: '{!! route('sales.users.info.print', $user->id) !!}', 
		data: {type: type, filter_range: '{{ $filter_range }}', date_from: '{{ $filter_sdate }}', date_to: '{{ $filter_edate }}'}, 
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
	
function showNotice(msg) {
	jQuery('#message .modal-header .modal-title').text("@lang('sales/title.download_report_title')");
	jQuery('#message .modal-body').html(msg);
	
	jQuery('#message').modal('show');
}		
</script>
@stop
