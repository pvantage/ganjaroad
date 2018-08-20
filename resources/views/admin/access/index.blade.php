@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('access/title.accesslist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('access/title.access')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('access/title.access')</li>
        <li class="active">@lang('access/title.accesslist')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15 access-table">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="comment" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('access/title.accesslist')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('access/table.id')</th>
                            <th>@lang('access/table.user')</th>
                            <th>@lang('access/table.contact')</th>
                            <th>@lang('access/table.email')</th>
                            <th>@lang('access/table.phone')</th>
                            <th>@lang('access/table.granted')</th>
                            <th>@lang('access/table.created_at')</th>
                            <th>@lang('access/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($access))
                        @foreach ($access as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{!! Form::Author($item->user_id) !!}</td>
                                <td>{{ $item->contact }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
								<td data-order="{{ ($item->granted) ? 1 : 0 }}">{!! Form::Access($item->granted) !!}</td>
                                <td>{{ $item->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ URL::to('admin/access/' . $item->id . '/edit' ) }}">
										<i class="livicon"
											data-name="edit"
											data-size="18"
											data-loop="true"
											data-c="#428BCA"
											data-hc="#428BCA"
											title="@lang('access/table.edit')">
										</i>
									</a>
                                    <a href="{{ route('confirm-delete/access', $item->id) }}" data-toggle="modal" data-target="#delete_confirm">
										<i class="livicon"
											data-name="remove-alt"
											data-size="18"
											data-loop="true"
											data-c="#f56954"
											data-hc="#f56954"
											title="@lang('access/table.blog')">
										</i>
									</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
		$('#table').DataTable({
			responsive: true,
			"aaSorting": []
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