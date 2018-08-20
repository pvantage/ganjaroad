@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('nearme/title.nearmelist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('nearme/title.nearmes')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('nearme/title.nearme')</li>
        <li class="active">@lang('nearme/title.nearmes')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="map" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('nearme/title.nearmelist')
                </h4>
                <div class="pull-right">
                    <a href="{{ URL::to('admin/nearme/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('nearme/table.title')</th>
                            <th>@lang('nearme/table.author')</th>
                            <th>@lang('nearme/table.email')</th>
                            <th>@lang('nearme/table.phone')</th>
                            <th>@lang('nearme/table.category')</th>
                            <th>@lang('nearme/table.created_at')</th>
							<th>@lang('nearme/table.paid')</th>
							<th>@lang('nearme/table.published')</th>
							<th>@lang('nearme/table.approved')</th>
                            <th>@lang('nearme/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($nearmes))
                        @foreach ($nearmes as $nearme)
                            <tr>
                                <td>{{ $nearme->title }}</td>
                                <td>{!! Form::Author($nearme->user_id) !!}</td>
                                <td>{{ $nearme->email }}</td>
                                <td>{{ $nearme->phone }}</td>
                                <td>{{ $nearme->category->title }}</td>
                                <td>{{ $nearme->created_at->diffForHumans() }}</td>
								<td data-search="{{ ($nearme->paid) ? 1 : 0 }}" data-order="{{ ($nearme->paid) ? 1 : 0 }}">{!! Form::Published($nearme->paid) !!}</td>
								<td data-search="{{ ($nearme->published) ? 1 : 0 }}" data-order="{{ ($nearme->published) ? 1 : 0 }}">{!! Form::Published($nearme->published) !!}</td>
								<td data-search="{{ ($nearme->approved) ? 1 : 0 }}" data-order="{{ ($nearme->approved) ? 1 : 0 }}">{!! Form::Published($nearme->approved) !!}</td>
                                <td>
                                    <a href="{{ URL::to('admin/nearme/' . $nearme->id . '/edit' ) }}"><i class="livicon"
                                                                                                     data-name="edit"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('nearme/table.update-nearme')"></i></a>
                                    <a href="{{ route('confirm-delete/nearme', $nearme->id) }}" data-toggle="modal"
                                       data-target="#delete_confirm"><i class="livicon" data-name="remove-alt"
                                                                        data-size="18" data-loop="true" data-c="#f56954"
                                                                        data-hc="#f56954"
                                                                        title="@lang('nearme/table.delete-nearme')"></i></a>
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
    $('#table').DataTable( {
        responsive: true,
        initComplete: function () {
            this.api().columns().every( function (index) {
                var column = this;
				if(index == 6 || index == 7 || index == 8){
					var select = $('<select class="filter"><option value=""></option></select>')
						.appendTo( $(column.header()) )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
	 
							column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
						});
						
						select.append( '<option value="1">@lang('general.yes')</option>' );
						select.append( '<option value="0">@lang('general.no')</option>' );
				} else if(index == 4) {
					var column = this;
					var select = $('<select><option value=""></option></select>')
						.appendTo( $(column.header()) )
						.on( 'change', function () {
							
							// Escape the expression so we can perform a regex match
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
			 
							column
								.search( val ? '^'+val+'$' : '', true, false )
								.draw();
						});
 
					column.data().unique().sort().each( function ( d, j ) {
						if(d) select.append( '<option value="'+d+'">'+d+'</option>' )
					});
				}
            });
        }
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