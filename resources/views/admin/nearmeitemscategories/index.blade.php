@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('nearmeitemscategory/title.management')
@parent
@stop
@section('header_styles')

@stop

{{-- Montent --}}
@section('content')
<section class="content-header">
    <h1>@lang('nearmeitemscategory/title.management')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('nearmeitemscategory/title.nearmecategories')</a></li>
        <li class="active">@lang('nearmeitemscategory/title.nearmeitemscategorylist')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="map" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('nearmeitemscategory/title.nearmeitemscategorylist')
                    </h4>
                    <div class="pull-right">
                    <a href="{{ URL::to('admin/nearmeitemscategory/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>@lang('nearmeitemscategory/table.id')</th>
                                    <th>@lang('nearmeitemscategory/table.name')</th>
                                    <th>@lang('nearmeitemscategory/table.numberofcategories')</th>
                                    <th>@lang('nearmeitemscategory/table.published')</th>
                                    <th>@lang('nearmeitemscategory/table.created_at')</th>
                                    <th>@lang('nearmeitemscategory/table.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
							
                            @if(!empty($nearmeitemscategories))
                                @foreach ($nearmeitemscategories as $category)
                                    <tr>
                                        <td>{{{ $category->id }}}</td>
                                        <td>{{{ $category->title }}}</td>
                                        <td>{{{ $category->nearmeItems()->count() }}}</td>
                                        <td data-search="{{ ($category->published) ? 1 : 0 }}" data-order="{{ ($category->published) ? 1 : 0 }}">{!! Form::Published($category->published) !!}</td>
                                        <td>{{{ $category->created_at->diffForHumans() }}}</td>
                                        <td>
                                            <a href="{{{ URL::to('admin/nearmeitemscategory/' . $category->id . '/edit' ) }}}"><i
                                                        class="livicon" data-name="edit" data-size="18" data-loop="true"
                                                        data-c="#428BCA" data-hc="#428BCA"
                                                        title="@lang('nearmeitemscategory/form.update-nearme')"></i></a>

                                            @if($category->nearmeItems()->count())
                                                <a href="#" data-toggle="modal" data-target="#nearmeitemscategory_exists" data-name="{!! $category->title !!}" class="nearmeitemscategory_exists">
                                                    <i class="livicon" data-name="warning-alt" data-size="18"
                                                       data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                       title="@lang('nearmeitemscategory/form.nearmeitemscategoryexists')"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('confirm-delete/nearmeitemscategory', $category->id) }}" data-toggle="modal" data-target="#delete_confirm">
                                                    <i class="livicon" data-name="remove-alt" data-size="18"
                                                       data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                       title="@lang('nearmeitemscategory/form.deletenearmeitemscategory')"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

@stop
{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable({responsive: true});
    });
</script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="nearmeitemscategory_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="nearmeitemscategory_exists" tabindex="-2" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    @lang('nearmeitemscategory/message.nearmeitemscategory_have_nearme')
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
        $(document).on("click", ".nearmeitemscategory_exists", function () {

            var group_name = $(this).data('name');
            $(".modal-header h4").text( group_name+" nearme category" );
        });</script>
@stop
