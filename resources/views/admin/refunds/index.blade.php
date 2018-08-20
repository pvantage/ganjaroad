@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('refunds/title.refunds')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('refunds/title.refunds')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
		<li class="">@lang('refunds/title.refunds')</li>
        <li class="active">@lang('refunds/title.refunds')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('refunds/title.refunds')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('refunds/table.id')</th>
                            <th>@lang('refunds/table.user')</th>
                            <th>@lang('refunds/table.item_type')</th>
                            <th>@lang('refunds/table.item_link')</th>
                            <th>@lang('refunds/table.amount')</th>
							<th>@lang('refunds/table.transaction_id')</th>
                            <th>@lang('refunds/table.created')</th>
                            <th>@lang('refunds/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
			responsive: true,
			processing: true,
			serverSide: true,
			order: [[ 0, 'desc' ]],
			ajax: '{!! route('refunds.datalist') !!}',
			columns: [
				{ data: 'id', name: 'id'},
				{ data: 'author', name: 'author', orderable: false, searchable: false},
				{ data: 'item_type', name: 'item_type'},
				{ data: 'item_link', name: 'item_link'},
				{ data: 'amount', name: 'amount'},
				{ data: 'transaction_id', name: 'transaction_id'},
				{ data: 'created_at', name:'created_at'},
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

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="item_approve_confirm_title" aria-hidden="true">
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