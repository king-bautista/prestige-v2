<header id="page-topbar">
    <div class="navbar-header">
        <div class="container-fluid">
            <div class="float-start">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <!-- Brand Logo -->
                    <a href='{{ url("portal") }}' class="brand-link">
                        <img src="{{ URL::to('images/prestige-interactive-logo.png') }}" alt="Prestige Logo" class="brand-image">
                    </a>
                </div>

                <button type="button"
                    class="btn btn-sm px-3 font-size-24 d-lg-none header-item waves-effect waves-light"
                    data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>

            <div class="float-end">
                <div class="dropdown d-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon waves-effect"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
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
                        <img class="rounded-circle header-profile-user"
                            src="{{ URL::to('images/user-icon.png') }}" alt="Header Avatar">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="#"><i class="fa fa-address-card" aria-hidden="true"></i></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                        <a href="#" class="nav-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <form id="logout-form" action='{{ url("portal/logout") }}' method="POST"
                                style="display: none;">
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
                                <a class="nav-link dropdown-toggle arrow-none" href="{{ $permission->link }}"
                                    id="topnav-client-management" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
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