@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('payments/title.payments')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('payments/title.payments')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
		<li class="">@lang('payments/title.payments')</li>
        <li class="active">@lang('payments/title.payments')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('payments/title.payments')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('payments/table.id')</th>
                            <th>@lang('payments/table.user')</th>
                            <th>@lang('payments/table.amount')</th>
							<th>@lang('payments/table.transaction_id')</th>
							<th>@lang('payments/table.coupon_code')</th>
							<th>@lang('payments/table.discount')</th>
							<th>@lang('payments/table.paid')</th>
                            <th>@lang('payments/table.created')</th>
                            <th>@lang('payments/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php /*
                    @if(!empty($payments))
                        @foreach ($payments as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{!! Form::Author($item->user_id) !!}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->transaction_id }}</td>
								<td data-order="{{ ($item->paid) ? 1 : 0 }}">{!! Form::Published($item->paid) !!}</td>
                                <td>{{ $item->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('confirm-delete/payments', $item->id) }}" data-toggle="modal" data-target="#delete_confirm">
										<i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="@lang('payments/table.delete-payments')"></i>
									</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
					*/ ?>
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
			ajax: '{!! route('payments.datalist') !!}',
			columns: [
				{ data: 'id', name: 'id'},
				{ data: 'author', name: 'author', orderable: false, searchable: false},
				{ data: 'amount', name: 'amount'},
				{ data: 'transaction_id', name: 'transaction_id'},
				{ data: 'coupon', name: 'coupon'},
				{ data: 'discount', name: 'discount'},
				{ data: 'paid', name: 'paid'},
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