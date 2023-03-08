<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PRESTIGE INTERACTIVE</title>
        <link rel="shortcut icon" href="{{ URL::to('images/prestige-favicon.png') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <!-- <link rel="stylesheet" href="{{ URL::to('plugins/fontawesome-free/css/all.min.css') }}"> -->
        <!-- Toastr -->
        <!-- <link rel="stylesheet" href="{{ URL::to('plugins/toastr/toastr.min.css') }}"> -->
        <!-- Theme style -->
        <!-- <link rel="stylesheet" href="{{ URL::to('dist/css/adminlte.min.css') }}"> -->
        <!-- <link rel="stylesheet" href="{{ URL::to('js/jquery-ui/jquery-ui.min.css') }}"> -->
        <!-- <link rel="stylesheet" href="{{ URL::to('css/custom.css') }}"> -->

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
        <div class="wrapper" id="app">
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
                                        @yield('Page-Title')
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
                                        
                                        @foreach($user->permissions as $permission)
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow-none" href="{{ $permission->link }}" id="topnav-client-management" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="{{ $permission->class_name }}"></i>{{ $permission->name }}
                                            </a>
                                            @if(count($permission->sub_permissions) > 0)
                                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="client-management">
                                                @foreach($permission->sub_permissions as $sub_menu)
                                                    @if($sub_menu->can_view)
                                                    <a href="{{ $sub_menu->link }}" class="dropdown-item">
                                                    <i class="{{ $sub_menu->class_name }}"></i>
                                                    {{ $sub_menu->name }}
                                                    </a>
                                                    @endif    
                                                @endforeach    
                                            </div>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <!-- <div class="row"> -->
                            <div>
                                @yield('content')
                            </div>
                        <!-- </div>  -->
                        <!-- end row -->
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
            </div>
            <!-- Main Footer -->
            <loader :is-visible="isLoading"></loader> @include('layout.portal.footer')
        </div>
        <!-- ./wrapper -->
        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{ URL::to('plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <!-- <script src="{{ URL::to('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
        <!-- Toastr -->
        <script src="{{ URL::to('plugins/toastr/toastr.min.js') }}"></script>
        <!-- ChartJS -->
        <script src="{{ URL::to('plugins/chart.js/Chart.min.js') }}"></script>
        <!-- AdminLTE App -->
        <!-- <script src="{{ URL::to('dist/js/portallte.min.js') }}"></script> -->
        <script src="{{ URL::to('js/helper.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <!--Morris Chart-->
        <script src="{{ URL::to('client/assets/libs/morris.js/morris.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/libs/raphael/raphael.min.js') }}"></script>
        <script src="{{ URL::to('client/assets/js/pages/dashboard.init.js') }}"></script>
        @stack('scripts') <!-- To include script links -->
    </body>
</html>
