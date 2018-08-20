<?php
$user = Sentinel::getUser();
?>
<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('admin') ? 'class="active"' : '') !!}>
        <a href="{{ route('dashboard') }}">
            <i class="livicon" data-name="home" data-size="18" data-c="#418BCA" data-hc="#418BCA"
               data-loop="true"></i>
            <span class="title">@lang('menu.dashboard')</span>
        </a>
    </li>

	@if ($user->hasAnyAccess(['admin.users.index', 'admin.users.edit', 'admin.users.create', 'delete.user']))
		<li {!! (Request::is('admin/users') || Request::is('admin/users/create') || Request::is('admin/user_profile') || Request::is('admin/users/*') || Request::is('admin/deleted_users') || Request::is('admin/access') || Request::is('admin/access/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="user" data-size="18" data-c="#6CC66C" data-hc="#6CC66C"
				   data-loop="true"></i>
				<span class="title">@lang('menu.users')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['admin.users.index']))
					<li {!! (Request::is('admin/users') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/users') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['admin.users.create']))
					<li {!! (Request::is('admin/users/create') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/users/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users_add_new')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['admin.users.create']))
					<li {!! (Request::is('admin/access') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/access') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users_access')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['delete.user']))
					<li {!! (Request::is('admin/deleted_users') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/deleted_users') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users_deleted')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['admin.users.index']) && $user->isSuperAdmin())
					<li {!! (Request::is('admin/users/imported') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/users/imported') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users_invited')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['admin.users.index']) && $user->isSuperAdmin())
					<li {!! (Request::is('admin/deleted_users') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/users/export') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users_export')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['groups', 'create.group']))
		<li {!! (Request::is('admin/groups') || Request::is('admin/groups/create') || Request::is('admin/groups/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="users" data-size="18" data-c="#418BCA" data-hc="#418BCA"
				   data-loop="true"></i>
				<span class="title">@lang('menu.groups')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['groups']))
					<li {!! (Request::is('admin/groups') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/groups') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.groups')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.group']))
					<li {!! (Request::is('admin/groups/create') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/groups/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.groups_add_new')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

    @if ($user->hasAnyAccess(['sales.*']))
		<li {!! (Request::is('admin/sales') || Request::is('admin/sales/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="money" data-size="18" data-c="#400BCA" data-hc="#400BCA"
				   data-loop="true"></i>
				<span class="title">@lang('menu.sales')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['sales.reps']))
					<li {!! (Request::is('admin/sales/reps') || Request::is('admin/sales/reps/*') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/sales/reps') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.reps_sales')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['sales.users']))
					<li {!! (Request::is('admin/sales/users') || Request::is('admin/sales/users/*') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/sales/users') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.users_sales')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['claim.approve']))
					<li {!! (Request::is('admin/sales/approve') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/sales/approve') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.sales_approve')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['coupons']))
					<li {!! (Request::is('admin/sales/coupons') ? 'class="active" id="active"' : '') !!}>
						<a href="{{ URL::to('admin/sales/coupons') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.coupons')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

    @if ($user->hasAnyAccess(['settings']))
        <li {!! (Request::is('admin/adrenewoption') || Request::is('admin/adrenewoption/*') ? 'class="active"' : '') !!}>
            <a href="#">
                <i class="livicon" data-name="divide-alt" data-size="18" data-c="#400BCA" data-hc="#400BCA"
                   data-loop="true"></i>
                <span class="title">@lang('menu.adrenewoptions')</span>
                <span class="fa arrow"></span>
            </a>
            <ul class="sub-menu">
                <li {!! (Request::is('admin/adrenewoption') ? 'class="active"' : '') !!}>
                    <a href="{{ URL::to('admin/adrenewoption') }}">
                        <i class="fa fa-angle-double-right"></i>
                        @lang('menu.adrenewoptions')
                    </a>
                </li>
                <li {!! (Request::is('admin/adrenewoption/create') ? 'class="active"' : '') !!}>
                    <a href="{{ URL::to('admin/adrenewoption/create') }}">
                        <i class="fa fa-angle-double-right"></i>
                        @lang('menu.adrenewoptions_add_new')
                    </a>
                </li>
            </ul>
        </li>
    @endif

	@if ($user->hasAnyAccess(['blocks', 'create.block', 'pages', 'create.page']))
		<li {!! (Request::is('admin/page') || Request::is('admin/page/*') || Request::is('admin/block') || Request::is('admin/block/*') || Request::is('admin/menu/*') || Request::is('admin/menu') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="doc-portrait" data-c="#fff600" data-hc="#fff600"
				   data-size="18" data-loop="true"></i>
				<span class="title">@lang('menu.pages')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['menu']))
					<li {!! (Request::is('admin/menu') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/menu') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.menu')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['blocks']))
					 <li {!! (Request::is('admin/block') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/block') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.blocks')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.block']))
					<li {!! (Request::is('admin/block/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/block/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.blocks_add_new')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['pages']))
					<li {!! (Request::is('admin/page') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/page') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.pages')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.page']))
					<li {!! (Request::is('admin/page/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/page/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.pages_add_new')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['classifiedschema', 'classifiedcategories', 'classifiedcategories.recreate', 'classifieds', 'create.classified']))
		<li {!! (Request::is('admin/classified') || Request::is('admin/classified/*') || Request::is('admin/classifiedschema') || Request::is('admin/classifiedschema/*') || Request::is('admin/classifiedcategory') || Request::is('admin/classifiedcategory/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="list" data-c="#0aa73a" data-hc="#0aa73a"
				   data-size="18" data-loop="true"></i>
				<span class="title">@lang('menu.classifieds')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['classifiedschema']))
					<li {!! (Request::is('admin/classifiedschema') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/classifiedschema') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.classifieds_schema')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['classifiedcategories.recreate']))
					<li {!! (Request::is('admin/classifiedschema/recreate') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/classifiedschema/recreate') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.classifieds_recreate')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['classifiedcategories']))
					<li {!! (Request::is('admin/classifiedcategory') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/classifiedcategory') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.classifieds_category_list')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['classifieds']))
					<li {!! (Request::is('admin/classified') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/classified') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.classifieds_classifieds_list')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.classified']))
					<li {!! (Request::is('admin/page/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/classified/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.classifieds_add_new')
						</a>
					</li>
				@endif
			</ul>
		</li>
    @endif

	@if ($user->hasAnyAccess(['nearmecategories', 'create.nearmecategory', 'nearmes', 'create.nearme']))
		<li {!! ((Request::is('admin/nearmecategory') || Request::is('admin/nearmecategory/create') || Request::is('admin/nearme') ||  Request::is('admin/nearme/create')) || Request::is('admin/nearme/*') || Request::is('admin/nearmecategory/*') || Request::is('admin/nearmeitemscategory') || Request::is('admin/nearmeitemscategory/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="map" data-c="#67C5DF" data-hc="#67C5DF" data-size="18"
				   data-loop="true"></i>
				<span class="title">@lang('menu.nearme')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['nearmecategories']))
					<li {!! (Request::is('admin/nearmecategory') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/nearmecategory') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.nearme_categories')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.nearmecategory']))
					<li {!! (Request::is('admin/nearmecategory/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/nearmecategory/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.nearme_categories_add_new')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['nearmes']))
					<li {!! (Request::is('admin/nearme') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/nearme') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.nearme_list')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['nearmes']))
					<li {!! (Request::is('admin/nearme/approval') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/nearme/approval') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.nearme_approval_list')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.nearme']))
					<li {!! (Request::is('admin/nearme/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/nearme/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.nearme_add_new')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['nearmeitemscategory']))
					<li {!! (Request::is('admin/nearmeitemscategory') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/nearmeitemscategory') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.nearme_items_category_add_new')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['adscompanies', 'adspositions', 'ads', 'create.ads']))
		<li {!! (Request::is('admin/ads') || Request::is('admin/adspositions') || Request::is('admin/adscompanies') || Request::is('admin/adspositions/*') || Request::is('admin/adscompanies/*') || Request::is('admin/ads/*') || Request::is('admin/adspositions/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="presentation" data-c="#c600ff" data-hc="#c600ff"
				   data-size="18" data-loop="true"></i>
				<span class="title">@lang('menu.ads')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['adscompanies']))
					<li {!! (Request::is('admin/adscompanies') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/adscompanies') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.ads_companies')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['adspositions']))
					<li {!! (Request::is('admin/adspositions') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/adspositions') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.ads_positions')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['ads']))
					<li {!! (Request::is('admin/ads') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/ads') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.ads_list')
						</a>
					</li>
				@endif
                @if ($user->hasAccess(['ads']))
					<li {!! (Request::is('admin/ads/pending') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/ads/pending') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.ads_pending')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.ads']))
					<li {!! (Request::is('admin/ads/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/ads/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.ads_add_new')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['blogcategories', 'create.blogcategory', 'blogs', 'create.blog']))
		<li {!! ((Request::is('admin/blogcategory') || Request::is('admin/blogcategory/create') || Request::is('admin/blog') ||  Request::is('admin/blog/create')) || Request::is('admin/blog/*') || Request::is('admin/blogcategory/*') ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="comment" data-c="#F89A14" data-hc="#F89A14" data-size="18"
				   data-loop="true"></i>
				<span class="title">@lang('menu.blog')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['blogcategories']))
					<li {!! (Request::is('admin/blogcategory') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/blogcategory') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.blog_categories')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.blogcategory']))
					<li {!! (Request::is('admin/blogcategory/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/blogcategory/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.blog_category_add_new')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['blogs']))
					<li {!! (Request::is('admin/blog') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/blog') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.blogs')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.blog']))
					<li {!! (Request::is('admin/blog/create') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/blog/create') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.blog_add_new')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['reporteditems', 'issues']))
		<li {!! ((Request::is('admin/reporteditems') || Request::is('admin/issues')) ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="flag" data-c="#ff0000" data-hc="#ff0000" data-size="18"
				   data-loop="true"></i>
				<span class="title">@lang('menu.reporteditems')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['reporteditems']))
					<li {!! (Request::is('admin/reporteditems') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/reporteditems') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.reporteditems_items')
						</a>
					</li>
				@endif

				@if ($user->hasAccess(['issues']))
					<li {!! (Request::is('admin/issues') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/issues') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.issues_items')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['payments']))
		<li {!! ((Request::is('admin/payments') || Request::is('admin/plans')) ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="credit-card" data-c="#ffffff" data-hc="#ffffff" data-size="18"
				   data-loop="true"></i>
				<span class="title">@lang('menu.payments')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['payments']))
					<li {!! (Request::is('admin/payments') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/payments') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.payments')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['plans']))
					<li {!! (Request::is('admin/plans') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/plans') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.plans')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['refunds']))
					<li {!! (Request::is('admin/refund') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/refund') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.refunds')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['reports.ads', 'reports.nearme', 'reports.classified']))
        <li {!! ((Request::is('admin/reports') || Request::is('admin/reports/*')) ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="linechart" data-c="#eef442" data-hc="#eef442" data-size="18" data-loop="true"></i>
				<span class="title">@lang('menu.reports')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['reports.classified']))
					<li {!! (Request::is('admin/reports/classified') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/reports/classified') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.reports_classified')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['reports.nearme']))
					<li {!! (Request::is('admin/reports/nearme') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/reports/nearme') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.reports_nearme')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['reports.ads']))
					<li {!! (Request::is('admin/reports/ads') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/reports/ads') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.reports_ads')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if ($user->hasAnyAccess(['settings']))
        <li {!! ((Request::is('admin/settings') || Request::is('admin/settings/*')) ? 'class="active"' : '') !!}>
			<a href="#">
				<i class="livicon" data-name="settings" data-c="#0006ff" data-hc="#0006ff" data-size="18"
				   data-loop="true"></i>
				<span class="title">@lang('menu.settings')</span>
				<span class="fa arrow"></span>
			</a>
			<ul class="sub-menu">
				@if ($user->hasAccess(['settings']))
					<li {!! (Request::is('admin/settings') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/settings') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.settings_list')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['cache.clear']))
					<li {!! (Request::is('admin/settings/cache') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/settings/cache') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.settings_cache')
						</a>
					</li>
				@endif
				@if ($user->hasAccess(['create.nearme']))
					<li {!! (Request::is('admin/settings/import') ? 'class="active"' : '') !!}>
						<a href="{{ URL::to('admin/settings/import') }}">
							<i class="fa fa-angle-double-right"></i>
							@lang('menu.settings_import')
						</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

</ul>
