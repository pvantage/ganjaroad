@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('page/title.pagelist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('page/title.pages')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('page/title.page')</li>
        <li class="active">@lang('page/title.pages')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('page/title.pagelist')
                </h4>
                <div class="pull-right">
                    <a href="{{ URL::to('admin/page/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('page/table.id')</th>
                            <th>@lang('page/table.title')</th>
                            <th>@lang('page/table.published')</th>
                            <th>@lang('page/table.created_at')</th>
                            <th>@lang('page/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($pages))
                        @foreach ($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ $page->title }}</td>
								<td data-order="{{ ($page->published) ? 1 : 0 }}">{!! Form::Published($page->published) !!}</td>
                                <td>{{ $page->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ URL::to('admin/page/' . $page->id . '/show' ) }}"><i class="livicon"
                                                                                                     data-name="info"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('page/table.view-page-comment')"></i></a>
                                    <a href="{{ URL::to('admin/page/' . $page->id . '/edit' ) }}"><i class="livicon"
                                                                                                     data-name="edit"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('page/table.update-page')"></i></a>
                                    <a href="{{ route('confirm-delete/page', $page->id) }}" data-toggle="modal"
                                       data-target="#delete_confirm"><i class="livicon" data-name="remove-alt"
                                                                        data-size="18" data-loop="true" data-c="#f56954"
                                                                        data-hc="#f56954"
                                                                        title="@lang('page/table.delete-page')"></i></a>
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