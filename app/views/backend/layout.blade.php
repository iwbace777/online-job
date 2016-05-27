@extends('main')
    @section('page-styles')        
        {{ HTML::style('/assets/metronic/admin/layout/css/layout.css') }}
        {{ HTML::style('/assets/metronic/admin/layout/css/themes/blue.css') }}
        {{ HTML::style('/assets/metronic/admin/layout/css/custom.css') }}
        {{ HTML::style('/assets/css/style_backend.css') }}
    @stop

    @section('body')
        <body class="page-header-fixed page-quick-sidebar-over-content">
            @section('header')
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="{{ URL::route('backend.dashboard') }}">
                            <img src="/assets/img/logo.png" alt="logo" class="logo-default" style="height: 32px; margin-top: 10px;">
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        @if (Session::has('admin_id'))
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-quick-sidebar-toggler">
    					        <a href="#" class="dropdown-toggle">
					                {{ Session::get('admin_name') }}
    					        </a>
    				        </li>                        
                            <li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="{{ URL::route('backend.auth.logout') }}" class="dropdown-toggle">
                                    <i class="icon-logout"></i> Sign Out
                                </a>
                            </li>
                        </ul>
                        @endif
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <div class="clearfix"></div>
            @show

            @section('main')
            <?php if (!isset($pageNo)) { $pageNo = 1; } ?>
            <div class="page-container">
                <div class="page-sidebar-wrapper">
                    <div class="page-sidebar navbar-collapse collapse">
                        <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
                            <li class="sidebar-toggler-wrapper">
                                <div class="sidebar-toggler"></div>
                            </li>
                            <li class="start <?php echo ($pageNo == 1) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.dashboard') }}">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Dashboard</span>
                                </a>
                            </li>
                            
                            <li class="<?php echo ($pageNo == 2) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.user') }}">
                                    <i class="icon-users"></i>
                                    <span class="title">User Management</span>
                                </a>
                            </li>
                            
                            <li class="<?php echo ($pageNo == 15) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.business') }}">
                                    <i class="fa fa-building"></i>
                                    <span class="title">Business Management</span>
                                </a>
                            </li>
                            
                            <li class="<?php echo ($pageNo == 4) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.job') }}">
                                    <i class="icon-diamond"></i>
                                    <span class="title">Job Management</span>
                                </a>
                            </li>
                            <li class="<?php echo ($pageNo == 13) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.city') }}">
                                    <i class="fa fa-map-marker"></i>
                                    <span class="title">City Management</span>
                                </a>
                            </li>                            
                            <li class="<?php echo ($pageNo == 5) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.category') }}">
                                    <i class="fa fa-tag"></i>
                                    <span class="title">Category Management</span>
                                </a>
                            </li>
                            <li class="<?php echo ($pageNo == 6) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.setting') }}">
                                    <i class="icon-settings"></i>
                                    <span class="title">Settings</span>
                                </a>
                            </li>
                            
                            <li class="<?php echo ($pageNo == 19) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.connection-require') }}">
                                    <i class="fa fa-legal"></i>
                                    <span class="title">Bids Requires</span>
                                </a>
                            </li>                            
                            
                            <li class="<?php echo ($pageNo == 7) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.email') }}">
                                    <i class="icon-envelope-open"></i>
                                    <span class="title">Email Templates</span>
                                </a>
                            </li>
                            
                            <li class="<?php echo ($pageNo == 18) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.newsletter') }}">
                                    <i class="icon-envelope-open"></i>
                                    <span class="title">Newsletter Management</span>
                                </a>
                            </li>
                            
                            <!-- li class="<?php echo ($pageNo == 8) ? "active" : "";?>">
                                <a href="#">
                                    <i class="fa fa-ticket"></i>
                                    <span class="title">Ticket Management</span>
                                </a>
                            </li -->
                            <li class="<?php echo ($pageNo == 9) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.package') }}">
                                    <i class="fa fa-cubes"></i>
                                    <span class="title">Purchase Packages</span>
                                </a>
                            </li>
                            <li class="<?php echo ($pageNo == 14) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.plan') }}">
                                    <i class="fa fa-cubes"></i>
                                    <span class="title">Subscribe Packages</span>
                                </a>
                            </li>
                            
                            <li class="<?php echo ($pageNo == 17) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.buy.history') }}">
                                    <i class="fa fa-cubes"></i>
                                    <span class="title">Bids Purchase Request</span>
                                </a>
                            </li>
                                                        
                            <li class="<?php echo ($pageNo == 16) ? "active" : "";?>">
                                <a href="{{ URL::route('backend.purchase.history') }}">
                                    <i class="fa fa-money"></i>
                                    <span class="title">Transactions</span>
                                </a>
                            </li>                                                        
                            <!-- li class="last <?php echo ($pageNo == 10) ? "active" : "";?>">
                                <a href="#">
                                    <i class="fa fa-credit-card"></i>
                                    <span class="title">Bid Purchase Request</span>
                                </a>
                            </li -->
                            <!-- li class="<?php echo ($pageNo == 11) ? "active" : "";?>">
                                <a href="#">
                                    <i class="icon-user-unfollow"></i>
                                    <span class="title">Abandon User</span>
                                </a>
                            </li>
                            <li class="<?php echo ($pageNo == 12) ? "active" : "";?>">
                                <a href="#">
                                    <i class="icon-puzzle"></i>
                                    <span class="title">Abandon Job</span>
                                </a>
                            </li -->
                        </ul>
                    </div>
                </div>
                <div class="page-content-wrapper">
                    <div class="page-content">
                        @yield('breadcrumb')
                        @yield('content')
                    </div>
                </div>
            </div>
            @show

            @section('footer')
                <div class="page-footer footer-background">
                    <div class="page-footer-inner">
                         &copy; Copyright 2015 | All Rights Reserved | Powered by Finternet-Group
                    </div>
                    <div class="page-footer-tools">
                        <span class="go-top">
                        <i class="fa fa-angle-up"></i>
                        </span>
                    </div>
                </div>
            @show
        </body>
    @stop

    @section('page-scripts')
        {{ HTML::script('/assets/metronic/global/scripts/metronic.js') }}
        {{ HTML::script('/assets/metronic/admin/layout/scripts/layout.js') }}
        {{ HTML::script('/assets/metronic/admin/layout/scripts/quick-sidebar.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/bootbox/bootbox.min.js') }}
        <script>
        jQuery(document).ready(function() {       
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init() // init quick sidebar

            $("a#js-a-delete").click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                bootbox.confirm("Are you sure?", function(result) {
                    if (result) {
                        window.location.href = url;
                    }
                });
            });
        });
        </script>        
    @stop
@stop
