@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('adspositions/title.management')
@parent
@stop
@section('header_styles')

@stop

{{-- Montent --}}
@section('content')
<section class="content-header">
    <h1>@lang('adspositions/title.management')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('adspositions/title.ads')</a></li>
        <li class="active">@lang('adspositions/title.adspositionslist')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="presentation" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('adspositions/title.adspositionslist')
                    </h4>
                    <div class="pull-right">
                    <a href="{{ URL::to('admin/adspositions/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>@lang('adspositions/table.id')</th>
                                    <th>@lang('adspositions/table.name')</th>
                                    <th>@lang('adspositions/table.code')</th>
                                    <th>@lang('adspositions/table.numberofads')</th>
									<th>@lang('adspositions/table.published')</th>
                                    <th>@lang('adspositions/table.created_at')</th>
                                    <th>@lang('adspositions/table.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
							
                            @if(!empty($adspositions))
                                @foreach ($adspositions as $position)
                                    <tr>
                                        <td>{{{ $position->id }}}</td>
                                        <td>{{{ $position->title }}}</td>
                                        <td>{{{ $position->slug }}}</td>
                                        <td>{{{ $position->ads()->count() }}}</td>
										<td data-order="{{ ($position->published) ? 1 : 0 }}">{!! Form::Published($position->published) !!}</td>
                                        <td>{{{ $position->created_at->diffForHumans() }}}</td>
                                        <td>
                                            <a href="{{{ URL::to('admin/adspositions/' . $position->id . '/edit' ) }}}"><i
                                                        class="livicon" data-name="edit" data-size="18" data-loop="true"
                                                        data-c="#428BCA" data-hc="#428BCA"
                                                        title="@lang('adspositions/form.update-position')"></i></a>

                                            @if($position->ads()->count())
                                                <a href="#" data-toggle="modal" data-target="#adspositions_exists" data-name="{!! $position->title !!}" class="adspositions_exists">
                                                    <i class="livicon" data-name="warning-alt" data-size="18"
                                                       data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                       title="@lang('adspositions/form.adspositionsexists')"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('confirm-delete/adspositions', $position->id) }}" data-toggle="modal" data-target="#delete_confirm">
                                                    <i class="livicon" data-name="remove-alt" data-size="18"
                                                       data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                       title="@lang('adspositions/form.deleteadspositions')"></i>
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
    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="adspositions_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="adspositions_exists" tabindex="-2" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    @lang('adspositions/message.adspositions_have_ads')
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
        $(document).on("click", ".adspositions_exists", function () {

            var group_name = $(this).data('name');
            $(".modal-header h4").text( group_name+" ads position" );
        });</script>
@stop
