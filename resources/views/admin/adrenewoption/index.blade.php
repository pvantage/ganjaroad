@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('adrenewoption/title.ad-renew-option')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('adrenewoption/title.ad-renew-option')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
		<li class="">@lang('adrenewoption/title.ad-renew-option')</li>
        <li class="active">@lang('adrenewoption/title.ad-renew-option')</li>
    </ol>
</section>
<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('adrenewoption/title.ad-renew-option')
                </h4>
                <div class="pull-right">
                    <a href="{{ URL::to('admin/adrenewoption/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('adrenewoption/table.id')</th>
                            <th>@lang('adrenewoption/table.renewal_period_name')</th>
                            <th>@lang('adrenewoption/table.renewal_time')</th>
                            <th>@lang('adrenewoption/table.renewal_time_period')</th>
                            <th>@lang('adrenewoption/table.renewal_type')</th>
                            <th>@lang('adrenewoption/table.renewal_discount')</th>
                            <th>@lang('adrenewoption/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($adrenewoptions))
                        @foreach ($adrenewoptions as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->renewal_period_name  }}</td>
                                <td>{{ $item->renewal_time }}</td>
                                <td>@lang('adrenewoption/table.'.$item->renewal_time_period)</td>
                                <td>@lang('adrenewoption/table.'.$item->renewal_type)</td>
                                <td>{{ $item->renewal_discount  }}%</td>
                                <td>
                                    <a href="{{ URL::to('admin/adrenewoption/' . $item->id . '/show' ) }}"><i class="livicon"
                                                                                                     data-name="info"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('adrenewoption/table.view-ad-renew-option')"></i></a>
                                    <a href="{{ URL::to('admin/adrenewoption/' . $item->id . '/edit' ) }}"><i class="livicon"
                                                                                                     data-name="edit"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('page/table.update-ad-renew-option')"></i></a>
                                    <a href="{{ route('confirm-delete/adrenewoption', $item->id) }}" data-toggle="modal"
                                       data-target="#delete_confirm"><i class="livicon" data-name="remove-alt"
                                                                        data-size="18" data-loop="true" data-c="#f56954"
                                                                        data-hc="#f56954"
                                                                        title="@lang('page/table.delete-ad-renew-option')"></i></a>
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
