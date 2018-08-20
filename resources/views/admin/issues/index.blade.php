@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('issues/title.issues')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('issues/title.issues')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li class="">@lang('issues/title.issuesitems')</li>
        <li class="active">@lang('issues/title.issues')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('issues/title.issues')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('issues/table.id')</th>
                            <th>@lang('issues/table.type')</th>
                            <th>@lang('issues/table.item_title')</th>
                            <th>@lang('issues/table.issue_type')</th>
                            <th>@lang('issues/table.comment')</th>
                            <th>@lang('issues/table.approve')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($issues))
                        @foreach ($issues as $item)
							{{--*/ if(!$item->isActive()) continue /*--}}
                            <tr>
                                <td>{{ $item->item->id }}</td>
                                <td>{{ $item->type }}</td>
                                <td>
								@if($item->type == 'classified')
									<a href="{{ URL::to('admin/classified/' . $item->item_id . '/edit' ) }}" target="_blank">{{ $item->classified->title }}</a>
								@else
									{{ $item->item_id }}
								@endif
								</td>
                                <td>{{ $item->getType() }}</td>
                                <td>{{ $item->comment }}</td>
                                <td>
                                    <a href="{{ route('confirm/issues', $item->item_id) }}" data-toggle="modal"
                                       data-target="#approve_confirm" title="@lang('issues/table.approve')">
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