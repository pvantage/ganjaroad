@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.review_pending')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/payments.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li class="active">@lang('front/general.review_pending')</li>
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.review_pending')</h1>
		</div>
		<hr>
		<div class="content text-center review-wrapper review-pending">
			@if($items)
				@foreach($items as $key => $type)
					@include('frontend/payments/pending_table', array('type' => $key, 'items' => $type))
				@endforeach
			@else
				<p>@lang('front/general.review_pending_empty')</p>
			@endif
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="payment_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('body').on('hidden.bs.modal', '.modal', function () {
		jQuery(this).removeData('bs.modal');
	});
	
	jQuery('#table').DataTable({
		"columnDefs": [ {
		  "targets"  : 'no-sort',
		  "orderable": false,
		  "order": []
		}]
	});
});
</script>
@stop
