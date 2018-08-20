@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('adscompanies/title.management')
@parent
@stop
@section('header_styles')

@stop

{{-- Montent --}}
@section('content')
<section class="content-header">
    <h1>@lang('adscompanies/title.management')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('adscompanies/title.adscompanies')</a></li>
        <li class="active">@lang('adscompanies/title.adscompanieslist')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="comment" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('adscompanies/title.adscompanieslist')
                    </h4>
                    <div class="pull-right">
                    <a href="{{ URL::to('admin/adscompanies/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                         <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>@lang('adscompanies/table.id')</th>
                                    <th>@lang('adscompanies/table.name')</th>
                                    <th>@lang('adscompanies/table.ads_count')</th>
                                    <th>@lang('adscompanies/table.created_at')</th>
                                    <th>@lang('adscompanies/table.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($adscompanies))
                                @foreach ($adscompanies as $company)
                                    <tr>
                                        <td>{{{ $company->id }}}</td>
                                        <td>{{{ $company->title }}}</td>
                                        <td>{{{ $company->ads()->count() }}}</td>
                                        <td>{{{ $company->created_at->diffForHumans() }}}</td>
                                        <td>
                                            <a href="{{{ URL::to('admin/adscompanies/' . $company->id . '/edit' ) }}}"><i
                                                        class="livicon" data-name="edit" data-size="18" data-loop="true"
                                                        data-c="#428BCA" data-hc="#428BCA"
                                                        title="@lang('adscompanies/form.update-company')"></i></a>

                                            @if($company->ads()->count())
                                                <a href="#" data-toggle="modal" data-target="#adscompanies_exists" data-name="{!! $company->title !!}" class="adscompanies_exists">
                                                    <i class="livicon" data-name="warning-alt" data-size="18"
                                                       data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                       title="@lang('adscompanies/form.adscompaniesexists')"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('confirm-delete/adscompanies', $company->id) }}" data-toggle="modal" data-target="#delete_confirm">
                                                    <i class="livicon" data-name="remove-alt" data-size="18"
                                                       data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                       title="@lang('adscompanies/form.deleteadscompanies')"></i>
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
    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="adscompanies_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="adscompanies_exists" tabindex="-2" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    @lang('adscompanies/message.adscompanies_have_ads')
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
        $(document).on("click", ".adscompanies_exists", function () {

            var group_name = $(this).data('name');
            $(".modal-header h4").text( '@lang('general.notice')' );
        });</script>
@stop
