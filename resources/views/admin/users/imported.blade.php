@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Users List
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('users/title.impoted_users')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('users/title.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('users/title.users')</a></li>
        <li class="active">@lang('users/title.impoted_users')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('users/title.impoted_users')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered " id="table">
                    <thead>
                        <tr class="filters">
                            <th>@lang('users/table.id')</th>
                            <th>@lang('users/table.email')</th>
                            <th>@lang('users/table.business')</th>
                            <th>@lang('users/table.loggedin')</th>
                            <th>@lang('users/table.created_at')</th>
                            <th>@lang('users/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($users))
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->email }}</td>
								<td>
									@if($user->nearme->count())
										{{ $user->nearme->first()->title }}
									@endif
								</td>
                                
								<td data-search="{{ ($user->last_login) ? 1 : 0 }}" data-order="{{ ($user->last_login) ? 1 : 0 }}">{!! Form::Published($user->last_login) !!}</td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ URL::to('admin/users/' . $user->id ) }}">
                                       <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view user"></i>
                                    </a>
                                    <a href="{{ URL::to('admin/users/' . $user->id ).'/edit' }}">
                                       <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update user"></i>
                                    </a>
                                    <?php if ((Sentinel::getUser()->id != $user->id) && ($user->id != 1)): ?>
                                        <a href="{{ URL::to('admin/users/'. $user->id .'/confirm-delete') }}" data-toggle="modal" data-target="#delete_confirm">
                                            <i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete user"></i>
                                        </a>
                                    <?php endif ?>
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

$(document).ready(function() {
    $('#table').DataTable( {
        responsive: true,
        initComplete: function () {
            this.api().columns().every( function (index) {
                var column = this;
				if(index == 3){
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
});
</script>
@stop
