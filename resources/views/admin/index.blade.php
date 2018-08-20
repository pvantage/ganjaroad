@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('admin.dashboard')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/pages/calendar_custom.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="all" href="{{ asset('assets/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/only_dashboard.css') }}"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">

@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>@lang('admin.welcome')</h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="#">
                    <i class="livicon" data-name="home" data-size="16" data-color="#333" data-hovercolor="#333"></i>
                    @lang('admin.dashboard')
                </a>
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
		
			@if($user->hasAccess('admin.users.index'))
				<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
					<!-- Trans label pie charts strats here-->
					<div class="palebluecolorbg no-radius">
						<div class="panel-body squarebox square_boxs">
							<div class="col-xs-12 pull-left nopadmar">
								<div class="row">
									<div class="square_box col-xs-7 pull-left">
										<span>@lang('admin.registrated_users')</span>
										<div class="number" id="allUsers"></div>
									</div>
									<i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff"
									   data-hc="#fff" data-s="70"></i>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<small class="stat-label">@lang('admin.activated_users')</small>
										<h4 id="activeUsers"></h4>
									</div>
									<div class="col-xs-6 text-right">
										<small class="stat-label">@lang('admin.published_users')</small>
										<h4 id="publishedUsers"></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
					<!-- Trans label pie charts strats here-->
					<div class="redbg no-radius">
						<div class="panel-body squarebox square_boxs">
							<div class="col-xs-12 pull-left nopadmar">
								<div class="row">
									<div class="square_box col-xs-7 pull-left">
										<span>@lang('admin.waiting_approval')</span>
										<div class="number" id="waitingApproval"></div>
									</div>
									<i class="livicon pull-right" data-name="clock" data-l="true" data-c="#fff"
									   data-hc="#fff" data-s="70"></i>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<small class="stat-label">@lang('admin.reps_approval')</small>
										<h4 id="repsApproval"></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
        </div>
        
        <div class="row">
			@if($user->hasAccess('ads'))
				<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
					<!-- Trans label pie charts strats here-->
					<div class="bg-purple no-radius">
						<div class="panel-body squarebox square_boxs">
							<div class="col-xs-12 pull-left nopadmar">
								<div class="row">
									<div class="square_box col-xs-7 pull-left">
										<span>@lang('admin.ads_all')</span>

										<div class="number" id="ads"></div>
									</div>
									<i class="livicon pull-right" data-name="piggybank" data-l="true" data-c="#fff"
									   data-hc="#fff" data-s="70"></i>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<small class="stat-label">@lang('admin.active')</small>
										<h4 id="publishedAds"></h4>
									</div>
									<div class="col-xs-6 text-right">
										<small class="stat-label">@lang('admin.approval')</small>
										<h4 id="unPublishedAds"></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
			
			@if($user->hasAccess('nearmes'))
				<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
					<!-- Trans label pie charts strats here-->
					<div class="bg-aqua no-radius">
						<div class="panel-body squarebox square_boxs">
							<div class="col-xs-12 pull-left nopadmar">
								<div class="row">
									<div class="square_box col-xs-7 pull-left">
										<span>@lang('admin.all_nearme')</span>

										<div class="number" id="nearme"></div>
									</div>
									<i class="livicon pull-right" data-name="map" data-l="true" data-c="#fff"
									   data-hc="#fff" data-s="70"></i>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<small class="stat-label">@lang('admin.active')</small>
										<h4 id="publishedNearme"></h4>
									</div>
									<div class="col-xs-6 text-right">
										<small class="stat-label">@lang('admin.approval')</small>
										<h4 id="unPublishedNearme"></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
            
			@if($user->hasAccess('classifieds'))
				<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
					<!-- Trans label pie charts strats here-->
					<div class="lightbluebg no-radius">
						<div class="panel-body squarebox square_boxs">
							<div class="col-xs-12 pull-left nopadmar">
								<div class="row">
									<div class="square_box col-xs-7 text-right">
										<span>@lang('admin.classifieds_all')</span>

										<div class="number" id="classifieds"></div>
									</div>
									<i class="livicon  pull-right" data-name="list" data-l="true" data-c="#fff"
									   data-hc="#fff" data-s="70"></i>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<small class="stat-label">@lang('admin.published')</small>
										<h4 id="publishedClassifieds"></h4>
									</div>
									<div class="col-xs-6 text-right">
										<small class="stat-label">@lang('admin.approval')</small>
										<h4 id="unPublishedClassifieds"></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
			
			@if($user->hasAccess('issues'))
				<div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
					<div class="redbg no-radius">
						<div class="panel-body squarebox square_boxs">
							<div class="col-xs-12 pull-left nopadmar">
								<div class="row">
									<div class="square_box col-xs-7 text-right">
										<span>@lang('admin.issued_items')</span>

										<div class="number" id="issues"></div>
									</div>
									<i class="livicon  pull-right" data-name="flag" data-l="true" data-c="#fff"
									   data-hc="#fff" data-s="70"></i>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<small class="stat-label">@lang('admin.reported')</small>
										<h4 id="reportedItems"></h4>
									</div>
									<div class="col-xs-6 text-right">
										<small class="stat-label">@lang('admin.issues')</small>
										<h4 id="issuedItems"></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
        <!--/row-->
        <div class="clearfix"></div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/countUp.js/js/countUp.js') }}"></script>
<script type="text/javascript">
	var useOnComplete = false,
		useEasing = false,
		useGrouping = false,
		options = {
			useEasing : useEasing, // toggle easing
			useGrouping : useGrouping, // 1,000,000 vs 1000000
			separator : ',', // character to use as a separator
			decimal : '.' // character to use as a decimal
		}

		var user_start_count = 100;
		var classifieds_start_count = 100;
		var ads_start_count = 100;
		var nearme_start_count = 100;
		var count = 0;
		
		count = new CountUp("allUsers", user_start_count, {!! $counters['all_users'] !!}, 0, 3, options);
        count.start();
        count = new CountUp("activeUsers", user_start_count, {!! $counters['active_users'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("publishedUsers", user_start_count, {!! $counters['published_users'] !!}, 0, 3, options);
        count.start();
        
        count = new CountUp("waitingApproval", user_start_count, {!! $counters['users_approval'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("repsApproval", user_start_count, {!! $counters['reps_approval'] !!}, 0, 3, options);
        count.start();
		
		count = new CountUp("classifieds", classifieds_start_count, {!! $counters['all_classifieds'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("publishedClassifieds", classifieds_start_count, {!! $counters['active_classifieds'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("unPublishedClassifieds", classifieds_start_count, {!! $counters['approval_classifieds'] !!}, 0, 3, options);
        count.start();
		
		count = new CountUp("ads", ads_start_count, {!! $counters['all_ads'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("publishedAds", ads_start_count, {!! $counters['active_ads'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("unPublishedAds", ads_start_count, {!! $counters['pending_ads'] !!}, 0, 3, options);
        count.start();
		
		count = new CountUp("nearme", nearme_start_count, {!! $counters['all_nearme'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("publishedNearme", nearme_start_count, {!! $counters['active_nearme'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("unPublishedNearme", nearme_start_count, {!! $counters['approval_nearme'] !!}, 0, 3, options);
        count.start();
		
		count = new CountUp("issues", nearme_start_count, {!! $counters['all_issues'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("reportedItems", nearme_start_count, {!! $counters['reported'] !!}, 0, 3, options);
        count.start();
		count = new CountUp("issuedItems", nearme_start_count, {!! $counters['issued'] !!}, 0, 3, options);
        count.start();
</script>
@stop
