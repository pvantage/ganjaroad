@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('sales/title.approve')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('sales/title.approve')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('sales/title.sales')</li>
        <li class="active">@lang('sales/title.approve')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('sales/title.approve')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('sales/table.user')</th>
							<th>@lang('sales/table.amount')</th>
                            <th>@lang('sales/table.claimed_by')</th>
                            <th>@lang('sales/table.claimed_at')</th>
                            <th>@lang('sales/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($users))
                        @foreach($users as $user)
                            <tr>
                                <td>{!! Form::Author($user->user_id) !!}</td>
								<td>{{ TemplateHelper::convertPrice($user->user->getAmountSpent()) }}</td>
                                <td>{!! Form::Author($user->admin_id) !!}</td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                <td>
									<a href="{{ route('confirm-approve', $user->id) }}" data-toggle="modal" data-target="#sales_modal">
										<i class="livicon" data-name="check-circle-alt" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="@lang('sales/table.approve_user')"></i>
									</a>
									<a href="{{ route('confirm-disapprove', $user->id) }}" data-toggle="modal" data-target="#sales_modal">
										<i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="@lang('sales/table.disapprove_user')"></i>
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

<div class="modal fade" id="sales_modal" tabindex="-1" role="dialog" aria-labelledby="sales_modal" aria-hidden="true">
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