@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('block/title.blocklist')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('block/title.blocks')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>@lang('block/title.block')</li>
        <li class="active">@lang('block/title.blocks')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="doc-portrait" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('block/title.blocklist')
                </h4>
                <div class="pull-right">
                    <a href="{{ URL::to('admin/block/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                </div>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('block/table.id')</th>
                            <th>@lang('block/table.title')</th>
                            <th>@lang('block/table.slug')</th>
							<th>@lang('nearme/table.published')</th>
                            <th>@lang('block/table.created_at')</th>
                            <th>@lang('block/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($blocks))
                        @foreach ($blocks as $block)
                            <tr>
                                <td>{{ $block->id }}</td>
                                <td>{{ $block->title }}</td>
                                <td>{{ $block->slug }}</td>
								<td data-order="{{ ($block->published) ? 1 : 0 }}">{!! Form::Published($block->published) !!}</td>
                                <td>{{ $block->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ URL::to('admin/block/' . $block->id . '/show' ) }}"><i class="livicon"
                                                                                                     data-name="info"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('block/table.view-block-comment')"></i></a>
                                    <a href="{{ URL::to('admin/block/' . $block->id . '/edit' ) }}"><i class="livicon"
                                                                                                     data-name="edit"
                                                                                                     data-size="18"
                                                                                                     data-loop="true"
                                                                                                     data-c="#428BCA"
                                                                                                     data-hc="#428BCA"
                                                                                                     title="@lang('block/table.update-block')"></i></a>
                                    <a href="{{ route('confirm-delete/block', $block->id) }}" data-toggle="modal"
                                       data-target="#delete_confirm"><i class="livicon" data-name="remove-alt"
                                                                        data-size="18" data-loop="true" data-c="#f56954"
                                                                        data-hc="#f56954"
                                                                        title="@lang('block/table.delete-block')"></i></a>
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