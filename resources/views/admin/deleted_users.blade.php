@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Deleted users
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
                <h1>Deleted users</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                            Dashboard
                        </a>
                    </li>
                    <li><a href="#">Users</a> </li>
                    <li class="active">Deleted users</li>
                </ol>
            </section>
            <!-- Main content -->
         <section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="livicon" data-name="users-remove" data-size="18" data-c="#ffffff" data-hc="#ffffff"></i>
                    Deleted Users List
                </h4>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr class="filters">
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>User E-mail</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{!! $user->first_name !!}</td>
                            <td>{!! $user->last_name !!}</td>
                            <td>{!! $user->email !!}</td>
                            <td>{!! $user->created_at->diffForHumans() !!}</td>
                            <td>
                                <a href="{{ route('restore/user', $user->id) }}"><i class="livicon" data-name="user-flag" data-c="#6CC66C" data-hc="#6CC66C" data-size="18"></i></a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

        
    @stop

{{-- page level scripts --}}
@section("footer_scripts")
<script type="text/javascript">
	$(document).ready(function() {
		$('#table').DataTable({responsive: true});
	});
</script>
@stop