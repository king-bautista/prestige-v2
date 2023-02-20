<!-- <!doctype html> -->
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>PRESTIGE INTERACTIVE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::to('client/assets/images/favicon.ico') }}">
        
        <!-- Bootstrap Css -->
        <link href="{{ URL::to('client/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ URL::to('client/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ URL::to('client/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ URL::to('client/assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body data-topbar="dark" data-layout="horizontal">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="container-fluid">
                        <div class="float-start">
                            <!-- LOGO -->
                            <div class="navbar-brand-box">
                                <!-- Brand Logo -->
                                <a href='{{ url("portal") }}' class="brand-link">
                                    <img src="{{ URL::to('images/prestige-interactive-logo.png') }}" alt="Prestige Logo" class="brand-image" style="opacity: .8">
                                </a>
                            </div>
    
                            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </div>
    
                        <div class="float-end">
    
                            <!-- App Search-->
                            <form class="app-search d-none d-lg-inline-block">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <span class="fa fa-search"></span>
                                </div>
                            </form>
                            		
                            <div class="dropdown d-inline-block d-lg-none ms-2">
                                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
    
                                    <form class="p-3">
                                        <div class="form-group m-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                
    
                            <div class="dropdown d-inline-block ms-1">
                                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti-bell"></i>
                                    <span class="badge bg-danger rounded-pill">3</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                    aria-labelledby="page-header-notifications-dropdown">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h5 class="m-0"> Notifications (258) </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-simplebar style="max-height: 230px;">
                                        <a href="javascript:void(0);" class="text-reset notification-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                    <span class="avatar-title border-success rounded-circle ">
                                                        <i class="mdi mdi-cart-outline"></i>
                                                    </span>
                                                </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Your order is placed</h6>
                                                    <div class="text-muted">
                                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
    
                                        <a href="javascript:void(0);" class="text-reset notification-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                    <span class="avatar-title border-warning rounded-circle ">
                                                        <i class="mdi mdi-message"></i>
                                                    </span>
                                                </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">New Message received</h6>
                                                    <div class="text-muted">
                                                        <p class="mb-1">You have 87 unread messages</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
    
                                        <a href="javascript:void(0);" class="text-reset notification-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                    <span class="avatar-title border-info rounded-circle ">
                                                        <i class="mdi mdi-glass-cocktail"></i>
                                                    </span>
                                                </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Your item is shipped</h6>
                                                    <div class="text-muted">
                                                        <p class="mb-1">It is a long established fact that a reader will</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
    
                                        <a href="javascript:void(0);" class="text-reset notification-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                    <span class="avatar-title border-primary rounded-circle ">
                                                        <i class="mdi mdi-cart-outline"></i>
                                                    </span>
                                                </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Your order is placed</h6>
                                                    <div class="text-muted">
                                                        <p class="mb-1">Dummy text of the printing and typesetting industry.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
    
                                        <a href="javascript:void(0);" class="text-reset notification-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                    <span class="avatar-title border-warning rounded-circle ">
                                                        <i class="mdi mdi-message"></i>
                                                    </span>
                                                </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">New Message received</h6>
                                                    <div class="text-muted">
                                                        <p class="mb-1">You have 87 unread messages</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="p-2 border-top">
                                        <a class="btn btn-sm btn-link font-size-14 w-100 text-center" href="javascript:void(0)">
                                            View all
                                        </a>
                                    </div>
                                </div>
                            </div>
    
                            <div class="dropdown d-inline-block">
                                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="rounded-circle header-profile-user" src="{{ URL::to('client/assets/images/users/user-4.jpg') }}"
                                        alt="Header Avatar">
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a class="dropdown-item" href="#"><i class="fa fa-user" aria-hidden="true"></i></i> Profile</a>
                                    <a class="dropdown-item" href="#"><i class="fa fa-google-wallet" aria-hidden="true"></i> My Wallet</a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"><i class="fa fa-cog" aria-hidden="true"></i> Settings<span class="badge bg-success ms-auto">11</span></a>
                                    <a class="dropdown-item" href="#"><i class="fa fa-lock" aria-hidden="true"></i> Lock screen</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <form id="logout-form" action='{{ url("portal/logout") }}' method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                                    
                        </div>
                    </div>
                </div>

                <div class="top-navigation">
                    <div class="page-title-content">
                        <div class="container-fluid">
                            <!-- start page title -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="page-title-box">
                                        <h4>Topbar Dark</h4>
                                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Prestige Interactive</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Horizontal</a></li>
                                            <li class="breadcrumb-item active">Topbar Dark</li>
                                        </ol>
                                        </nav>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                            <!-- end page title -->
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="topnav">
                            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                                <div class="collapse navbar-collapse" id="topnav-menu-content">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <a class="nav-link" href='{{ url("portal") }}'>
                                                <i class="ti-dashboard"></i>Dashboard
                                            </a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-client-management" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti-user"></i>Client Management 
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="client-management">
                                                <a href='{{ url("portal/roles") }}' class="dropdown-item">Roles</a>
                                                <a href='{{ url("portal/modules") }}' class="dropdown-item">Modules</a>
                                                <a href='{{ url("portal/users") }}' class="dropdown-item">User</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>

   
            </header>
    

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="card mini-stat bg-primary">
                                    <div class="card-body mini-stat-img">
                                        <div class="mini-stat-icon"> 
                                            <i class="fa fa-cube float-end" aria-hidden="true"></i>
                                        </div>
                                        <div class="text-white">
                                            <h6 class="text-uppercase mb-3 font-size-16 text-white">Orders</h6>
                                            <h2 class="mb-4 text-white">1,587</h2>
                                            <span class="badge bg-info"> +11% </span> <span class="ms-2">From previous period</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card mini-stat bg-primary">
                                    <div class="card-body mini-stat-img">
                                        <div class="mini-stat-icon">
                                        <i class="fa fa-angle-double-up float-end" aria-hidden="true"></i></i>
                                        </div>
                                        <div class="text-white">
                                            <h6 class="text-uppercase mb-3 font-size-16 text-white">Revenue</h6>
                                            <h2 class="mb-4 text-white">$46,782</h2>
                                            <span class="badge bg-danger"> -29% </span> <span class="ms-2">From previous period</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card mini-stat bg-primary">
                                    <div class="card-body mini-stat-img">
                                        <div class="mini-stat-icon">
                                        <i class="fa fa-money float-end" aria-hidden="true"></i>
                                        </div>
                                        <div class="text-white">
                                            <h6 class="text-uppercase mb-3 font-size-16 text-white">Average Price</h6>
                                            <h2 class="mb-4 text-white">$15.9</h2>
                                            <span class="badge bg-warning"> 0% </span> <span class="ms-2">From previous period</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card mini-stat bg-primary">
                                    <div class="card-body mini-stat-img">
                                        <div class="mini-stat-icon">
                                        <i class="fa fa-briefcase float-end" aria-hidden="true"></i>
                                        </div>
                                        <div class="text-white">
                                            <h6 class="text-uppercase mb-3 font-size-16 text-white">Product Sold</h6>
                                            <h2 class="mb-4 text-white">1890</h2>
                                            <span class="badge bg-info"> +89% </span> <span class="ms-2">From previous period</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">

                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Monthly Earnings</h4>

                                        <div class="row text-center mt-4">
                                            <div class="col-6">
                                                <h5 class="font-size-20">$56241</h5>
                                                <p class="text-muted">Marketplace</p>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="font-size-20">$23651</h5>
                                                <p class="text-muted">Total Income</p>
                                            </div>
                                        </div>

                                        <div id="morris-donut-example" data-colors='["#f0f1f4","--bs-primary","--bs-info"]' class="morris-charts morris-charts-height" dir="ltr"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Email Sent</h4>

                                        <div class="row text-center mt-4">
                                            <div class="col-4">
                                                <h5 class="font-size-20">$ 89425</h5>
                                                <p class="text-muted">Marketplace</p>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="font-size-20">$ 56210</h5>
                                                <p class="text-muted">Total Income</p>
                                            </div>
                                            <div class="col-4">
                                                <h5 class="font-size-20">$ 8974</h5>
                                                <p class="text-muted">Last Month</p>
                                            </div>
                                        </div>

                                        <div id="morris-area-example" data-colors='["#f0f1f4","--bs-primary","--bs-info"]' class="morris-charts morris-charts-height" dir="ltr"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Monthly Earnings</h4>

                                        <div class="row text-center mt-4">
                                            <div class="col-6">
                                                <h5 class="font-size-20">$ 2548</h5>
                                                <p class="text-muted">Marketplace</p>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="font-size-20">$ 6985</h5>
                                                <p class="text-muted">Total Income</p>
                                            </div>
                                        </div>

                                        <div id="morris-bar-stacked" data-colors='["--bs-info","#f0f1f4"]' class="morris-charts morris-charts-height" dir="ltr"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end row -->

                        <div class="row">

                            <div class="col-xl-4 col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-3">Inbox</h4>
                                        <div class="inbox-wid">
                                            <a href="#" class="text-dark">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img float-start me-3"><img src="{{ URL::to('client/assets/images/users/user-1.jpg') }}" class="avatar-sm rounded-circle" alt=""></div>
                                                    <h6 class="inbox-item-author mb-1 font-size-16">Misty</h6>
                                                    <p class="inbox-item-text text-muted mb-0">Hey! there I'm available...</p>
                                                    <p class="inbox-item-date text-muted">13:40 PM</p>
                                                </div>
                                            </a>
                                            <a href="#" class="text-dark">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img float-start me-3"><img src="{{ URL::to('client/assets/images/users/user-2.jpg') }}" class="avatar-sm rounded-circle" alt=""></div>
                                                    <h6 class="inbox-item-author mb-1 font-size-16">Melissa</h6>
                                                    <p class="inbox-item-text text-muted mb-0">I've finished it! See you so...</p>
                                                    <p class="inbox-item-date text-muted">13:34 PM</p>
                                                </div>
                                            </a>
                                            <a href="#" class="text-dark">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img float-start me-3"><img src="{{ URL::to('client/assets/images/users/user-3.jpg') }}" class="avatar-sm rounded-circle" alt=""></div>
                                                    <h6 class="inbox-item-author mb-1 font-size-16">Dwayne</h6>
                                                    <p class="inbox-item-text text-muted mb-0">This theme is awesome!</p>
                                                    <p class="inbox-item-date text-muted">13:17 PM</p>
                                                </div>
                                            </a>
                                            <a href="#" class="text-dark">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img float-start me-3"><img src="{{ URL::to('client/assets/images/users/user-4.jpg') }}" class="avatar-sm rounded-circle" alt=""></div>
                                                    <h6 class="inbox-item-author mb-1 font-size-16">Martin</h6>
                                                    <p class="inbox-item-text text-muted mb-0">Nice to meet you</p>
                                                    <p class="inbox-item-date text-muted">12:20 PM</p>
                                                </div>
                                            </a>
                                            <a href="#" class="text-dark">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img float-start me-3"><img src="{{ URL::to('client/assets/images/users/user-5.jpg') }}" class="avatar-sm rounded-circle" alt=""></div>
                                                    <h6 class="inbox-item-author mb-1 font-size-16">Vincent</h6>
                                                    <p class="inbox-item-text text-muted mb-0">Hey! there I'm available...</p>
                                                    <p class="inbox-item-date text-muted">11:47 AM</p>
                                                </div>
                                            </a>

                                            <a href="#" class="text-dark">
                                                <div class="inbox-item">
                                                    <div class="inbox-item-img float-start me-3"><img src="{{ URL::to('client/assets/images/users/user-6.jpg') }}" class="avatar-sm rounded-circle" alt=""></div>
                                                    <h6 class="inbox-item-author mb-1 font-size-16">Robert Chappa</h6>
                                                    <p class="inbox-item-text text-muted mb-0">Hey! there I'm available...</p>
                                                    <p class="inbox-item-date text-muted">10:12 AM</p>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-4 col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Recent Activity Feed</h4>

                                        <ol class="activity-feed mb-0">
                                            <li class="feed-item">
                                                <div class="feed-item-list">
                                                    <span class="date">Jun 25</span>
                                                    <span class="activity-text">Responded to need “Volunteer Activities”</span>
                                                </div>
                                            </li>
                                            <li class="feed-item">
                                                <div class="feed-item-list">
                                                    <span class="date">Jun 24</span>
                                                    <span class="activity-text">Added an interest “Volunteer Activities”</span>
                                                </div>
                                            </li>
                                            <li class="feed-item">
                                                <div class="feed-item-list">
                                                    <span class="date">Jun 23</span>
                                                    <span class="activity-text">Joined the group “Boardsmanship Forum”</span>
                                                </div>
                                            </li>
                                            <li class="feed-item">
                                                <div class="feed-item-list">
                                                    <span class="date">Jun 21</span>
                                                    <span class="activity-text">Responded to need “In-Kind Opportunity”</span>
                                                </div>
                                            </li>
                                        </ol>

                                        <div class="text-center">
                                            <a href="#" class="btn btn-sm btn-primary">Load More</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-4">
                                <div class="card widget-user">
                                    <div class="widget-user-desc p-4 text-center bg-primary position-relative">
                                        <i class="fas fa-quote-left h2 text-white-50"></i>
                                        <p class="text-white mb-0">The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe the same vocabulary. The languages only in their grammar.</p>
                                    </div>
                                    <div class="p-4">
                                        <div class="float-start mt-2 me-3">
                                            <img src="{{ URL::to('client/assets/images/users/user-2.jpg') }}" alt="" class="rounded-circle avatar-sm">
                                        </div>
                                        <h6 class="mb-1 font-size-16 mt-2">Marie Minnick</h6>
                                        <p class="text-muted mb-0">Marketing Manager</p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Yearly Sales</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div>
                                                    <h3>52,345</h3>
                                                    <p class="text-muted">The languages only differ grammar</p>
                                                    <a href="#" class="text-primary">Learn more <i class="mdi mdi-chevron-double-right"></i></a>
                                                </div>
                                            </div>
                                            <div class="col-md-8 text-end">
                                                <div id="sparkline" data-colors='["--bs-primary"]'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end row -->
                        
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Latest Transactions</h4>

                                        <div class="table-responsive">
                                            <table class="table align-middle table-centered table-vertical table-nowrap">

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-2.jpg') }}" alt="user-image" class="avatar-xs rounded-circle me-2" /> Herbert C. Patton
                                                        </td>
                                                        <td>Confirm</td>
                                                        <td>
                                                            $14,584
                                                            <p class="m-0 text-muted font-size-14">Amount</p>
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                            <p class="m-0 text-muted font-size-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-3.jpg') }}" alt="user-image" class="avatar-xs rounded-circle me-2" /> Mathias N. Klausen
                                                        </td>
                                                        <td><i class="mdi mdi-checkbox-blank-circle text-warning"></i> Waiting payment</td>
                                                        <td>
                                                            $8,541
                                                            <p class="m-0 text-muted font-size-14">Amount</p>
                                                        </td>
                                                        <td>
                                                            10/11/2016
                                                            <p class="m-0 text-muted font-size-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-4.jpg') }}" alt="user-image" class="avatar-xs rounded-circle me-2" /> Nikolaj S. Henriksen
                                                        </td>
                                                        <td><i class="mdi mdi-checkbox-blank-circle text-success"></i> Confirm</td>
                                                        <td>
                                                            $954
                                                            <p class="m-0 text-muted font-size-14">Amount</p>
                                                        </td>
                                                        <td>
                                                            8/11/2016
                                                            <p class="m-0 text-muted font-size-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-5.jpg') }}" alt="user-image" class="avatar-xs rounded-circle me-2" /> Lasse C. Overgaard
                                                        </td>
                                                        <td><i class="mdi mdi-checkbox-blank-circle text-danger"></i> Payment expired</td>
                                                        <td>
                                                            $44,584
                                                            <p class="m-0 text-muted font-size-14">Amount</p>
                                                        </td>
                                                        <td>
                                                            7/11/2016
                                                            <p class="m-0 text-muted font-size-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-6.jpg') }}" alt="user-image" class="avatar-xs rounded-circle me-2" /> Kasper S. Jessen
                                                        </td>
                                                        <td><i class="mdi mdi-checkbox-blank-circle text-success"></i> Confirm</td>
                                                        <td>
                                                            $8,844
                                                            <p class="m-0 text-muted font-size-14">Amount</p>
                                                        </td>
                                                        <td>
                                                            1/11/2016
                                                            <p class="m-0 text-muted font-size-14">Date</p>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Latest Orders</h4>

                                        <div class="table-responsive">
                                            <table class="table align-middle table-centered table-vertical table-nowrap mb-1">

                                                <tbody>
                                                    <tr>
                                                        <td>#12354781</td>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-1.jpg') }}" alt="user-image" class="avatar-xs me-2 rounded-circle" /> Riverston Glass Chair
                                                        </td>
                                                        <td><span class="badge rounded-pill bg-success">Delivered</span></td>
                                                        <td>
                                                            $185
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>#52140300</td>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-2.jpg') }}" alt="user-image" class="avatar-xs me-2 rounded-circle" /> Shine Company Catalina
                                                        </td>
                                                        <td><span class="badge rounded-pill bg-success">Delivered</span></td>
                                                        <td>
                                                            $1,024
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>#96254137</td>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-3.jpg') }}" alt="user-image" class="avatar-xs me-2 rounded-circle" /> Trex Outdoor Furniture Cape
                                                        </td>
                                                        <td><span class="badge rounded-pill bg-danger">Cancel</span></td>
                                                        <td>
                                                            $657
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>#12365474</td>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-4.jpg') }}" alt="user-image" class="avatar-xs me-2 rounded-circle" /> Oasis Bathroom Teak Corner
                                                        </td>
                                                        <td><span class="badge rounded-pill bg-warning">Shipped</span></td>
                                                        <td>
                                                            $8451
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>#85214796</td>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-5.jpg') }}" alt="user-image" class="avatar-xs me-2 rounded-circle" /> BeoPlay Speaker
                                                        </td>
                                                        <td><span class="badge rounded-pill bg-success">Delivered</span></td>
                                                        <td>
                                                            $584
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>#12354781</td>
                                                        <td>
                                                            <img src="{{ URL::to('client/assets/images/users/user-6.jpg') }}" alt="user-image" class="avatar-xs me-2 rounded-circle" /> Riverston Glass Chair
                                                        </td>
                                                        <td><span class="badge rounded-pill bg-success">Delivered</span></td>
                                                        <td>
                                                            $185
                                                        </td>
                                                        <td>
                                                            5/12/2016
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light">Edit</button>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                © <script>document.write(new Date().getFullYear())</script> Prestige Interactive <span class="d-none d-sm-inline-block"> - Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand.</span>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">

                <div class="rightbar-title d-flex align-items-center px-3 py-4">
            
                    <h5 class="m-0 me-2">Settings</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
           

                   <div class="px-4 py-2">
                    <h6 class="mb-3">Select Custome Colors</h6>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input theme-color" type="radio" name="theme-mode"
                                id="theme-default" value="default" onchange="document.documentElement.setAttribute('data-theme-mode', 'default')" checked>
                            <label class="form-check-label" for="theme-default">Default</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input theme-color" type="radio" name="theme-mode"
                                id="theme-red" value="red" onchange="document.documentElement.setAttribute('data-theme-mode', 'red')">
                            <label class="form-check-label" for="theme-red">Red</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input theme-color" type="radio" name="theme-mode"
                                id="theme-teal" value="teal" onchange="document.documentElement.setAttribute('data-theme-mode', 'teal')">
                            <label class="form-check-label" for="theme-teal">Teal</label>
                        </div>
                   </div>
           

                <h6 class="text-center mb-0 mt-3">Choose Layouts</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="{{ URL::to('client/assets/images/client/layout-1.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked />
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="{{ URL::to('client/assets/images/client/layout-2.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch" data-bsStyle="{{ URL::to('client/assets/css/bootstrap-dark.min.css') }}" data-appStyle="{{ URL::to('client/assets/css/app-dark.min.css') }}" />
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="{{ URL::to('client/assets/images/client/layout-3.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="form-check form-switch mb-5">
                        <input type="checkbox" class="form-check-input theme-choice" id="rtl-mode-switch" data-appStyle="{{ URL::to('client/assets/css/app-rtl.min.css') }}" />
                        <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label>
                    </div>

            
                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div> 
        
        <!-- JAVASCRIPT -->
        <!-- <script src="{{ URL::to('client/assets/libs/jquery/jquery.min.js') }}"></script> -->
        <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
        <!-- <script src="{{ URL::to('client/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
        <script src="{{ URL::to('client/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

        <!--Morris Chart-->
        <script src="{{ URL::to('client/assets/libs/morris.js/morris.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/raphael/raphael.min.js') }}"></script>

        <script src="{{ URL::to('client/assets/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <!-- <script src="{{ URL::to('client/assets/js/app.js') }}"></script> -->
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>

</html>