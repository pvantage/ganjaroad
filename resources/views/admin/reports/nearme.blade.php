@extends('admin/layouts/default')

{{-- Reports title --}}
@section('title')
    @lang('reports/title.nearme_reports')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('reports/title.reports')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('reports/title.reports')</li>
        <li class="active">@lang('reports/title.reports')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('reports/title.nearme_reports')
                </h4>
                <div class="pull-right">
                    <a href="{{ route('reports/export/nearme') }}" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-export"></i>@lang('reports/title.export')</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('reports/table.title')</th>
                            <th>@lang('reports/table.nearme_ad_type')</th>
                            <th>@lang('reports/table.user_fullname')</th>
                            <th>@lang('reports/table.user_email')</th>
                            <th>@lang('reports/table.user_state')</th>
                            <th>@lang('reports/table.user_city')</th>
                            <th>@lang('reports/table.ad_state')</th>
                            <th>@lang('reports/table.ad_city')</th>
                            <th>@lang('reports/table.phone')</th>
                            <th>@lang('reports/table.email')</th>
                            <!--<th>@lang('reports/table.user_groups')</th>--->
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

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});

    var table = $('#table').DataTable({
        responsive: true,
		processing: true,
		serverSide: true,
		order: [[ 2, 'asc' ]],
		ajax: '{!! route('reports.nearme.datalist') !!}',
		columns: [
			{ data: 'nearme_title', name: 'nearme_title'},
            { data: 'nearme_ad_type', name: 'nearme_ad_type'},
			{ data: 'fullname', name: 'fullname'},
			{ data: 'user_email', name: 'user_email'},
			{ data: 'user_state', name: 'user_state'},
			{ data: 'user_city', name: 'user_city'},
			{ data: 'nearme_state', name: 'nearme_state'},
			{ data: 'nearme_city', name: 'nearme_city'},
			{ data: 'nearme_phone', name: 'nearme_phone'},
			{ data: 'nearme_email', name: 'nearme_email'},
			//{ data: 'user_groups', name: 'user_groups', orderable: false, searchable: false},
		],
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
@stop
