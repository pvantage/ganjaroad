@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.my_ads')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/ads.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.my_ads')</li>
	</ol>               
@stop

@section('leftcol')
	@section('leftcol_content')
		@include('layouts/user_leftcol')
	@stop
	@parent
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@lang('front/general.ads')</h1>
		</div>
		<hr>
		<div class="content my-ads">
			@if(!empty($ads))
				<p <?php /*class="hidden-lg"*/ ?>>@lang('front/general.table_green_plus_notice')</p>
				<table class="table table-bordered has-actions" id="table">
					<thead>
						<tr class="filters">
							<th>@lang('ads/table.title')</th>
							<th>@lang('ads/table.position')</th>
							<th>@lang('ads/table.views')</th>
							<th>@lang('ads/table.clicks')</th>
							<th>@lang('ads/table.created_at')</th>
							<th class="no-sort">@lang('ads/table.published')</th>
							@if($require_approval)
							<th class="no-sort">@lang('ads/table.approved')</th>
							<th class="no-sort">@lang('ads/table.paid')</th>
							@endif
							<th class="no-sort">@lang('ads/table.actions')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($ads as $ad)
							<tr>
								<td>{{ $ad->title }}</td>
								<td>{{ $ad->positions->title }}</td>
								<td>{{ $ad->views }}</td>
								<td>{{ $ad->clicks }}</td>
								<td>{{ $ad->created_at->diffForHumans() }}</td>
								<td>{!! Form::Published($ad->published) !!}</td>
								@if($require_approval)
								<td>{!! Form::Published($ad->approved) !!}</td>
								<td>{!! Form::TableYesNo($ad->paid) !!}</td>
								@endif
								<td class="actions">
									<a href="{{ TemplateHelper::adEditLink($ad->id, $ad->slug) }}" title="@lang('ads/table.update-ad')">
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
									</a>

									<a href="{{ route('ad/confirm-delete/ad', $ad->id) }}" class="modal-link" title="@lang('ads/table.delete-ad')">
										<i class="fa fa-trash-o" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p>@lang('front/general.ads_empty_user')</p>
			@endif
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>

<div class="modal fade" id="modal_popup" tabindex="-1" role="dialog" aria-hidden="true">
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
		responsive: true,
		language: {
			"emptyTable": "@lang('ads/table.no_data_available')"
		},
		"columnDefs": [{
		  "targets"  : 'no-sort',
		  "orderable": false,
		  "order": []
		}]
	});
});
</script>
@stop
