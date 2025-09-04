<!-- header.blade.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <!-- Collapse/Expand Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Menu</span>
    </a>
    <!-- Sidebar Menu -->
    <div class="sidebar d-flex flex-column h-100">
        <nav class="flex-grow-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Manage Admin (submenu) -->
                <li class="nav-item {{ request()->is('admin/admin/listing','admin/role/listing','admin/resource/listing') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin.admin.listing','admin.role.listing','admin.resource.listing') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Manage Admin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ urlx('admin.admin.listing',[],true) }}" 
                            class="nav-link {{ request()->is('admin/admin/listing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admins</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ urlx('admin.role.listing',[],true) }}" 
                            class="nav-link {{ request()->is('admin/role/listing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ urlx('admin.resource.listing',[],true) }}" 
                            class="nav-link {{ request()->is('admin/resource/listing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Resources</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Manage API (submenu) -->
                <li class="nav-item {{ request()->is('admin/apiuser/listing','admin/apirole/listing','admin/apiresource/listing') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin.apiuser.listing','admin.apirole.listing','admin.apiresource.listing') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plug"></i>
                        <p>
                            Manage API
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ urlx('admin.apiuser.listing',[],true) }}" 
                            class="nav-link {{ request()->is('admin/apiuser/listing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>API Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ urlx('admin.apirole.listing',[],true) }}" 
                            class="nav-link {{ request()->is('admin/apirole/listing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>API Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ urlx('admin.apiresource.listing',[],true) }}" 
                            class="nav-link {{ request()->is('admin/apiresource/listing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>API Resources</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Other items -->
                <li class="nav-item">
                    <a href="{{ urlx('admin.event.listing',[],true) }}" 
                    class="nav-link {{ request()->is('admin/event/listing') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Manage Event</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ urlx('admin.cron.listing',[],true) }}" 
                    class="nav-link {{ request()->is('admin/cron/listing') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Manage Cron</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ urlx('admin.config.listing',[],true) }}" 
                    class="nav-link {{ request()->is('admin/config/listing') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Logout Button -->
        <div class="mt-auto p-3">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>
