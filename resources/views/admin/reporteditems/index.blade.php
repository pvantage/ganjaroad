@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('reporteditems/title.reportedlist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('reporteditems/title.reporteditems')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
		<li class="">@lang('reporteditems/title.reporteditems')</li>
        <li class="active">@lang('reporteditems/title.reporteditems')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('reporteditems/title.reportedlist')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('reporteditems/table.type')</th>
                            <th>@lang('reporteditems/table.item_title')</th>
                            <th>@lang('reporteditems/table.reason')</th>
                            <th>@lang('reporteditems/table.count_items')</th>
                            <th>@lang('reporteditems/table.approve')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($reported))
                        @foreach ($reported as $item)
                            <tr>
                                <td>{{ $item->type }}</td>
                                <td>
								@if($item->type == 'classified')
									<a href="{{ URL::to('admin/classified/' . $item->item_id . '/edit' ) }}">{{ $item->item->title }}</a>
								@else
									{{ $item->item_id }}
								@endif
								</td>
                                <td>{!! TemplateHelper::getReasonReported($item->item_id) !!}</td>
                                <td>{{ $item->items_count }}</td>
                                <td>
                                    <a href="{{ route('confirm/reporteditems', $item->item_id) }}" data-toggle="modal"
                                       data-target="#approve_confirm" title="@lang('reporteditems/table.approve')">
									   <i class="fa fa-check-circle" aria-hidden="true"></i>
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
        $('#table').DataTable({responsive: true});
    });
</script>

<div class="modal fade" id="approve_confirm" tabindex="-1" role="dialog" aria-labelledby="item_approve_confirm_title" aria-hidden="true">
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