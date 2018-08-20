@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    @lang('settings/title.settings')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}">
	
	<link href="{{ asset('assets/css/elements/checkbox.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop


{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        @lang('settings/title.settings')
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li class="active">
            @lang('settings/title.settings')
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="settings" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('settings/title.settings')
                    </h4>
                </div>
				
                <div class="panel-body">
					<div class="has-error">
						@if($errors->has())
						   @foreach ($errors->all() as $error)
								<span class="help-block">{{ $error }}</span>
						  @endforeach
						@endif
					</div>
					{!! Form::model($settings, array('url' => URL::to('admin/settings/edit'), 'method' => 'post', 'id' => 'settings_form', 'class' => 'form-horizontal bf', 'files'=> true)) !!}
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

						@foreach ($settings as $setting)
							{!! Form::Settings($setting, $errors) !!}
						@endforeach

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ route('dashboard') }}">
                                    @lang('button.cancel')
                                </a>
                                <button type="submit" class="btn btn-success">
                                    @lang('button.save')
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('.yesno-value').each(function() {
		$(this).bootstrapSwitch('state');
	});
	
	$('.multiselect-value').each(function() {
		$(this).multiselect({
			includeSelectAllOption: true,
            enableFiltering: true,
            filterBehavior: 'both',
			enableCaseInsensitiveFiltering: true,
        });
		$(this).multiselect('rebuild');
	});
	
	$('.yesno-value').on('switchChange.bootstrapSwitch', function(event, state) {
		var id = $(this).attr('data-config');

		$('input[name="'+id+'"]').val(+state);
	});
});

</script>
@stop