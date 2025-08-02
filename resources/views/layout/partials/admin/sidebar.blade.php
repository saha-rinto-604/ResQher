<!-- Sidebar s t a r t-->
<aside class="sidebar">
    <div class="sidebar-menu">
        <div class="sidebar-menu scroll-hide">
            <ul class="sidebar-dropdown-menu parent-menu-list">

                <!-- Single Menu -->
                <li class="sidebar-menu-item {{ Route::currentRouteName() === 'admin.dashboard' ? 'active-menu' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="parent-item-content">
                        <i class="ri-dashboard-line"></i>
                        <span class="on-half-expanded">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Route::currentRouteName() === 'admin.victims' ? 'active-menu' : '' }}">
                    <a href="{{ route('admin.victims') }}" class="parent-item-content">
                        <i class="ri-dashboard-line"></i>
                        <span class="on-half-expanded">Victims</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Route::currentRouteName() === 'admin.volunteers' ? 'active-menu' : '' }}">
                    <a href="{{ route('admin.volunteers') }}" class="parent-item-content">
                        <i class="ri-dashboard-line"></i>
                        <span class="on-half-expanded">Volunteers</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ Route::currentRouteName() === 'admin.law-enforcements' ? 'active-menu' : '' }}">
                    <a href="{{ route('admin.law-enforcements') }}" class="parent-item-content">
                        <i class="ri-dashboard-line"></i>
                        <span class="on-half-expanded">Law Enforcement</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
<!-- / Sidebar-->
