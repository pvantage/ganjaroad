@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('access/title.access')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <!--section starts-->
    <h1>@lang('access/title.access')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-c="#000" data-loop="true"></i>
                @lang('general.home')
            </a>
        </li>
        <li>
            <a href="#">@lang('access/title.access')</a>
        </li>
        <li class="active">@lang('access/title.edit')</li>
    </ol>
</section>
<!--section ends-->
<section class="content paddingleft_right15">
    <!--main content-->
    <div class="row">
        <div class="the-box no-border">
			<!-- errors -->
            <div class="has-error">
				@if($errors->has())
				   @foreach ($errors->all() as $error)
						<span class="help-block">{{ $error }}</span>
				  @endforeach
				@endif
            </div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">@lang('access/form.user')</div>
						<div class="col-sm-10">
							{!! Form::Author($access->user_id) !!}
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-2">@lang('access/form.type')</div>
						<div class="col-sm-10">
							{{ $access->type }}
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-2">@lang('access/form.business')</div>
						<div class="col-sm-10">
							@if($access->business) {{ $access->business }}
							@else &nbsp;
							@endif
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-2">@lang('access/form.contact')</div>
						<div class="col-sm-10">
							@if($access->contact) {{ $access->contact }}
							@else &nbsp;
							@endif
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-2">@lang('access/form.email')</div>
						<div class="col-sm-10">
							@if($access->email) {{ $access->email }}
							@else &nbsp;
							@endif
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-2">@lang('access/form.phone')</div>
						<div class="col-sm-10">
							@if($access->phone) {{ $access->phone }}
							@else &nbsp;
							@endif
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-2 ">@lang('access/form.address')</div>
						<div class="col-sm-10">
							@if($access->address) {{ $access->address }}
							@else &nbsp;
							@endif
						</div>
					</div>
					
					<div class="form-group">
						<p>&nbsp;</p>
					</div>
					
					<div class="form-group">
						<a href="{{ URL::to('admin/access/' . $access->id . '/grant' ) }}" class="btn btn-success">@lang('access/form.grant')</a>
						<a href="{!! URL::to('admin/access') !!}" class="btn btn-danger">@lang('access/form.cancel')</a>
					</div>
				</div>
			</div>
		</div>
    </div>
    <!--main content ends-->
</section>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<!--edit page-->
@stop