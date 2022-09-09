<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ URL::to('images/prestige-interactive-logo.png') }}" alt="Prestige Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">CMS</span>
    </a>
    <!-- Sidebar -->
    @if($user)
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ URL::to('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{$user->full_name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @foreach($user->permissions as $permission)
                    @if($permission->can_view)
                    <li class="nav-item">
                        <a href="{{ $permission->link }}" class="nav-link">
                            <i class="{{ $permission->class_name }}"></i>
                            <p>{{ $permission->name }}
                                @if(count($permission->sub_permissions) > 0)
                                <i class="fas fa-angle-left right"></i>
                                @endif
                            </p>
                        </a>
                        @if(count($permission->sub_permissions) > 0)
                        <ul class="nav nav-treeview">
                            @foreach($permission->sub_permissions as $sub_menu)
                                @if($sub_menu->can_view)
                                <li class="nav-item">
                                    <a href="{{ $sub_menu->link }}" class="nav-link">
                                        <i class="{{ $sub_menu->class_name }}"></i>
                                        <p>{{ $sub_menu->name }}</p>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endif
                @endforeach
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Account Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-edit"></i>
                            <p>User information</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                            </a>
                            <form id="logout-form" action='{{ url("admin/logout") }}' method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    @endif
<!-- /.sidebar -->
</aside>