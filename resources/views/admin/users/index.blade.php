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
    <h1>@lang('users/title.users')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('users/title.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('users/title.users')</a></li>
        <li class="active">@lang('users/title.users_list')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    @lang('users/title.users_list')
                </h4>
            </div>
            <br />
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr class="filters">
							<th>@lang('users/table.last_name')</th>
							<th class="desktop">@lang('users/table.first_name')</th>
                            <th class="never">@lang('users/table.id')</th>
							<th>@lang('users/table.spammer')</th>
                            <th>@lang('users/table.email')</th>
							<th>@lang('users/table.location')</th>
                            <th>@lang('users/table.status')</th>
                            <th>@lang('users/table.active')</th>
                            <th>@lang('users/table.created_at')</th>
                            <th>@lang('users/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php /*
                    @if(!empty($users))
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <?php 
                                    if($activation = Activation::completed($user)) echo 'Activated';
                                    else echo 'Pending';
                                    ?>
                                </td>
								<td data-order="{{ ($user->published) ? 1 : 0 }}">{!! Form::Published($user->published) !!}</td>
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
					*/ ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    <!-- row-->
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
</section>
@stop

{{-- page level scripts --}}

<div class="modal fade" id="claim_confirm" tabindex="-1" role="dialog" aria-labelledby="user_claim_confirm" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
	</div>
</div>

@section('footer_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').DataTable({
			responsive: true,
			processing: true,
			serverSide: true,
			order: [[ 8, 'desc' ]],
			ajax: '{!! route('users.data') !!}',
			columns: [
				{ data: 'last_name', name: 'last_name', responsivePriority: 10},
				{ data: 'first_name', name: 'first_name', responsivePriority: 15},
				{ data: 'id', name: 'id', responsivePriority: 1},
				{ data: 'spammer', name: 'spammer', responsivePriority: 40},
                { data: 'email', name: 'email', responsivePriority: 30},
                { data: 'location', name: 'location', orderable: false, searchable: false, responsivePriority: 20},
                { data: 'published', name: 'published', class: 'status-col', responsivePriority: 60},
                { data: 'activation', name:'activation', responsivePriority: 70},
                { data: 'created_at', name:'created_at', responsivePriority: 50},
                { data: 'actions', name: 'actions', orderable: false, searchable: false, responsivePriority: 80}
            ]
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
