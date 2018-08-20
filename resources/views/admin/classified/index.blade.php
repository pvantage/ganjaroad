@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('classified/title.classifiedlist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop

{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('classified/title.classifieds')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('classified/title.classified')</li>
        <li class="active">@lang('classified/title.classifieds')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
				<form action="{{ route('admin.classifieds') }}" type="GET">
					<div class="row">
						<h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
							@lang('classified/title.classifiedlist')
						</h4>
						<div class="pull-right">
							<a href="{{ URL::to('admin/classified/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
							<a href="javascript:void(0)" onclick="$(this).closest('form').submit()" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-saved"></i>@lang('classified/title.apply_filters')</a>
							<a href="{{ route('admin.classifieds') }}" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i>@lang('classified/title.remove_filters')</a>
						</div>
					</div>
					<div class="row">
						<div class="row header-filters">
							<div class="pull-right">
								<div class="pull-left">
									{!! Form::label('filter_published', trans('classified/table.published'), array('class' => 'control-label')) !!}
									{!! Form::select('filter_published', array('' => Lang::get('classified/title.choose_status'), 0 => Lang::get('general.no'), 1 => Lang::get('general.yes')), null, ['class' => 'select form-control', 'id' => 'filter_published']) !!}
								</div>
							</div>
						</div>
					</div>
                </form>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('classified/table.id')</th>
                            <th>@lang('classified/table.title')</th>
                            <th>@lang('classified/table.author')</th>
                            <th>@lang('classified/table.paid')</th>
                            <th>@lang('classified/table.published')</th>
                            <th>@lang('classified/table.active')</th>
							@if(TemplateHelper::getSetting('classified_approval'))
								<th>@lang('classified/table.approved')</th>
							@endif
                            <th>@lang('classified/table.reported')</th>
							<th>@lang('classified/table.is_visible')</th>
                            <th>@lang('classified/table.views')</th>
                            <th>@lang('classified/table.created_at')</th>
                            <th>@lang('classified/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php /*
                    @if(!empty($classifieds))
                        @foreach ($classifieds as $classified)
                            <tr>
                                <td>{{ $classified->id }}</td>
                                <td>{{ $classified->title }}</td>
                                <td>{!! Form::Author($classified->user_id) !!}</td>
                                <td data-search="{{ ($classified->paid) ? 1 : 0 }}" data-order="{{ ($classified->paid) ? 1 : 0 }}">{!! Form::Published($classified->paid) !!}</td>
                                <td data-search="{{ ($classified->published) ? 1 : 0 }}" data-order="{{ ($classified->published) ? 1 : 0 }}">{!! Form::Published($classified->published) !!}</td>
                                <td data-search="{{ ($classified->active) ? 1 : 0 }}" data-order="{{ ($classified->active) ? 1 : 0 }}">{!! Form::Published($classified->active) !!}</td>
                                @if(TemplateHelper::getSetting('classified_approval'))
									<td data-search="{{ ($classified->approved) ? 1 : 0 }}" data-order="{{ ($classified->approved) ? 1 : 0 }}">{!! Form::Published($classified->approved) !!}</td>
								@endif
                                <td data-search="{{ ($classified->isReported()) ? 1 : 0 }}" data-order="{{ ($classified->isReported()) ? 1 : 0 }}">{!! Form::Published($classified->isReported()) !!}</td>
                                <td>{{ $classified->views }}</td>
                                <td>{{ $classified->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ URL::to('admin/classified/' . $classified->id . '/edit' ) }}">
										<i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="@lang('classified/table.update-classified')"></i>
									</a>
                                    <a href="{{ route('confirm-delete/classified', $classified->id) }}" data-toggle="modal" data-target="#delete_confirm">
										<i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="@lang('classified/table.delete-classified')"></i>
									</a>
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
		ajax: {
			url: '{!! route('classifieds.datalist') !!}',
			data: function ( d ) {
				d.filters = [];
				@if(app('request')->input('filter_published') || app('request')->input('filter_published') == 0)
					d.filter_published = '{{ app('request')->input('filter_published') }}';
				@endif
			}
		},
		columns: [
			{ data: 'id', name: 'id'},
			{ data: 'title', name: 'title'},
			{ data: 'fullname', name: 'fullname', searchable: true},
			{ data: 'paid', name: 'paid'},
			{ data: 'published', name: 'published', orderable: false, searchable: false},
			{ data: 'active', name:'active'},
			@if(TemplateHelper::getSetting('classified_approval'))
				{ data: 'approved', name:'approved'},
			@endif
			{ data: 'reported', name:'reported'},
			{ data: 'is_visible', name:'is_visible', orderable: false, searchable: false},
			{ data: 'views', name:'views'},
			{ data: 'created_at', name:'created_at'},
			{ data: 'actions', name: 'actions', orderable: false, searchable: false}
		],
        initComplete: function () {
            this.api().columns().every( function (index) {
                var column = this;
				if(index == 3 /*|| index == 4*/ || index == 5 || index == 6){
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