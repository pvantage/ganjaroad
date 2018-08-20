<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Ganjaroad
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/jpeg" href="/favicon.jpg" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.responsive.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.css') }}" media="all" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/global.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/pages/toastr.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/toastr/css/toastr.css') }}" rel="stylesheet" type="text/css" />
    <!-- end of global css -->
    <!--page level css-->
    @yield('header_styles')
    @yield('header_js')
	<!--end of page level css-->

<body class="skin-josh admin-template">
<header class="header">
    <a href="{{ route('dashboard') }}" class="logo">
        <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if(Sentinel::getUser()->pic)
                            <img src="{!! url('/').'/uploads/users/'.Sentinel::getUser()->pic !!}" alt="img" height="35px" width="35px"
                                 class="img-circle img-responsive pull-left"/>
                        @else
                            <img src="{!! asset('assets/images/avatar.jpg') !!} " width="35"
                                 class="img-circle img-responsive pull-left" height="35" alt="riot">
                        @endif
                        <div class="riot">
                            <div>
                                {{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}
                                <span>
                                        <i class="caret"></i>
                                    </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            @if(Sentinel::getUser()->pic)
                                <img src="{!! url('/').'/uploads/users/'.Sentinel::getUser()->pic !!}" alt="img"
                                     class="img-circle img-bor"/>
                            @else
                                <img src="{!! asset('assets/images/avatar.jpg') !!}"
                                     class="img-responsive img-circle" alt="User Image">
                            @endif
                            <p class="topprofiletext">{{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</p>
                        </li>
                        <!-- Menu Body -->
                        <li>
                            <a href="{{ URL::route('users.show',Sentinel::getUser()->id) }}">
                                <i class="livicon" data-name="user" data-s="18"></i>
                                My Profile
                            </a>
                        </li>
                        <li role="presentation"></li>
                        <li>
                            <a href="{{ route('admin.users.edit', Sentinel::getUser()->id) }}">
                                <i class="livicon" data-name="gears" data-s="18"></i>
                                Account Settings
                            </a>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ URL::route('lockscreen',Sentinel::getUser()->id) }}">
                                    <i class="fa fa-angle-double-right"></i>
                                    Lock
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ URL::to('admin/logout') }}">
                                    <i class="livicon" data-name="sign-out" data-s="18"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar ">
            <div class="page-sidebar  sidebar-nav">
                <!-- BEGIN SIDEBAR MENU -->
                @include('admin.layouts._left_menu')
                <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>
    <aside class="right-side">

        <!-- Notifications -->
        @include('notifications')

                <!-- Content -->
        @yield('content')

    </aside>
    <!-- right-side -->
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top"
   data-toggle="tooltip" data-placement="left">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a>
<!-- global js -->
<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-select/bootstrap-select.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/admin.js') }}"></script>	
<script type="text/javascript" src="{{ asset('assets/vendors/toastr/js/toastr.min.js') }}" ></script>
<!-- end of global js -->
<!-- begin page level js -->
@yield('footer_scripts')
        <!-- end page level js -->
</body>
</html>
