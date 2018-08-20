@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('ads/title.adslist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('ads/title.ads')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('ads/title.ads')</li>
        <li class="active">@lang('ads/title.ads')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="presentation" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('ads/title.adslist')
                </h4>
                <div class="pull-right">
                    <a href="{{ URL::to('admin/ads/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
							<th class="desktop">@lang('ads/table.id')</th>
							<th>@lang('ads/table.paid')</th>
							<th>@lang('ads/table.approved')</th>
							<th>@lang('ads/table.published')</th>
                            <th>@lang('ads/table.author')</th>
                            <th>@lang('ads/table.title')</th>
							<th>@lang('ads/table.phone')</th>
							<th>@lang('ads/table.company')</th>
                            <th>@lang('ads/table.position')</th>
                            <th>@lang('ads/table.views')</th>
                            <th>@lang('ads/table.clicks')</th>
                            <th>@lang('ads/table.created_at')</th>
                            <th>@lang('ads/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php /*
                    @if(!empty($ads))
                        @foreach ($ads as $ad)
                            <tr>
                                <td>{!! Form::Author($ad->user_id) !!}</td>
                                <td>{{ $ad->title }}</td>
                                <td>@if($ad->company){{ $ad->companydetails->title }}@endif</td>
                                <td>{{ $ad->positions->title }}</td>
                                <td>{{ $ad->views }}</td>
                                <td>{{ $ad->clicks }}</td>
                                <td>{{ $ad->created_at->diffForHumans() }}</td>
								<td data-search="{{ ($ad->paid) ? 1 : 0 }}" data-order="{{ ($ad->paid) ? 1 : 0 }}">{!! Form::Published($ad->paid) !!}</td>
								<td data-search="{{ ($ad->published) ? 1 : 0 }}" data-order="{{ ($ad->published) ? 1 : 0 }}">{!! Form::Published($ad->published) !!}</td>
								<td data-search="{{ ($ad->approved) ? 1 : 0 }}" data-order="{{ ($ad->approved) ? 1 : 0 }}">{!! Form::Published($ad->approved) !!}</td>
                                <td>
									<a href="{{ URL::to('admin/ads/' . $ad->id . '/show' ) }}" target="_blank"><i class="livicon"
                                                                                                     data-name="info"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('ads/table.preview')"></i></a>
                                    <a href="{{ URL::to('admin/ads/' . $ad->id . '/edit' ) }}"><i class="livicon"
                                                                                                     data-name="edit"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('ads/table.update-ads')"></i></a>
                                    <a href="{{ route('confirm-delete/ads', $ad->id) }}" data-toggle="modal"
                                       data-target="#delete_confirm"><i class="livicon" data-name="remove-alt"
                                                                        data-size="18" data-loop="true" data-c="#f56954"
                                                                        data-hc="#f56954"
                                                                        title="@lang('ads/table.delete-ads')"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
					*/ ?>
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
    var table = $('#table').DataTable({
        responsive: true,
		processing: true,
		serverSide: true,
		order: [[ 0, 'desc' ]],
		ajax: '{!! route('ads.datalist') !!}',
		columns: [
			{ data: 'id', name: 'id'},
			{ data: 'paid', name: 'paid'},
			{ data: 'approved', name: 'approved'},
			{ data: 'published', name: 'published'},
			{ data: 'author', name: 'author', orderable: false, searchable: false},
			{ data: 'title', name: 'title', responsivePriority: 2},
            { data: 'phone', name: 'phone'},
			{ data: 'companydetails.title', name: 'companydetails.title'},
			{ data: 'position_id', name:'position_id'},
			{ data: 'views', name: 'views'},
			{ data: 'clicks', name: 'clicks'},
			{ data: 'created_at', name: 'created_at'},
			{ data: 'actions', name: 'actions', orderable: false, searchable: false, responsivePriority: 1 }
		],
        initComplete: function () {
            this.api().columns().every( function (index) {
                var column = this;
				if(index == 1 || index == 2 || index == 3) {
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
				} else if(index == 6 || index == 7) {
					var column = this;
					var class_name = '';
					if (index == 6) class_name = 'company-select';
					var select = $('<select class="' + class_name + '"><option value=""></option></select>')
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

					column.data().unique().sort().each( function ( d, j ){
						var element = $(d);
						var name = element.attr('name');

						if(d) select.append( '<option value="'+name+'">'+d+'</option>' );

					});

					$('.company-select').html($('.company-select option').sort(function (a, b) {
						return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
					}));
				}
            });
        }
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
