@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.my_classifieds')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/classifieds.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		<li><a href="{{ URL::to('my-account') }}">@lang('front/general.user_account')</a></li>
		<li class="active">@lang('front/general.my_classifieds')</li>
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
			<h1>@lang('front/general.classifieds')</h1>
		</div>
		<hr>
		<div class="content my-classified">
			@if(!empty($classifieds))
				<p>@lang('classified/table.unpublished_notice', ['date' => TemplateHelper::getClassifiedPublisedTime()])</p>
				<p>@lang('front/general.table_edit_notice')</p>
				<p <?php /*class="hidden-lg"*/ ?>>@lang('front/general.table_green_plus_notice')</p>
				<table class="table table-bordered has-actions" id="table">
					<thead>
						<tr class="filters">
							<th>@lang('classified/table.title')</th>
							<th>@lang('classified/table.category')</th>
							<th>@lang('classified/table.views')</th>
							<th>@lang('classified/table.created_at')</th>
							<th class="no-sort">@lang('classified/table.published')</th>
							@if($require_approval)
								<th class="no-sort">@lang('classified/table.approved')</th>
							@endif
							<th class="no-sort">@lang('classified/table.paid')</th>
							<th class="no-sort">@lang('classified/table.actions')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($classifieds as $classified)
							<tr>
								<td>{{ $classified->title }}</td>
								<td>@if($classified->last_category()) {{ $classified->last_category()->title }}@endif</td>
								<td>{{ $classified->views }}</td>
								<td data-search="{{ strtotime($classified->created_at) }}" data-order="{{ strtotime($classified->created_at) }}">{{ $classified->created_at->diffForHumans() }}</td>
								<td data-search="{{ ($classified->isActive()) ? 1 : 0 }}" data-order="{{ ($classified->isActive()) ? 1 : 0 }}">{!! Form::Published($classified->isActive()) !!}</td>
								@if($require_approval)
									<td data-search="{{ ($classified->approved) ? 1 : 0 }}" data-order="{{ ($classified->approved) ? 1 : 0 }}">
										{!! Form::Published($classified->approved) !!}
									</td>
								@endif
								<td>{!! Form::TableYesNo($classified->paid) !!}</td>
								<td class="actions">
									<a href="{{ TemplateHelper::classifiedEditLink($classified->id, $classified->slug) }}" title="@lang('classified/table.update-classified')">
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
									</a>
									<a href="{{ route('classifieditem/confirm-unpublish/classified', $classified->id) }}" class="modal-link">
										<i class="fa fa-times-circle" aria-hidden="true" title="@lang('classified/table.unpublish')"></i>
									</a>
									<a href="{{ route('classifieditem/confirm-delete/classified', $classified->id) }}" title="@lang('classified/table.delete-classified')" class="modal-link">
										<i class="fa fa-trash-o" aria-hidden="true"></i>
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p>@lang('front/general.classified_empty')</p>
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
			"emptyTable": "@lang('classified/table.no_data_available')"
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
