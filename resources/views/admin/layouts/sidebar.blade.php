<aside class="main-sidebar sidebar-dark-primary elevation-3">
    <a href="javascript:void(0);" class="brand-link">
        <img src="{{url(asset('storage/' .setting_value('company_small_logo')))}}" alt="Website Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ setting_value('company_name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                @canany(['city.view', 'amenity.view', 'propertytype.view'])
                    <li class="nav-header">MASTERS</li>

                    @can('city.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/cities') }}" class="nav-link {{ Route::currentRouteName() == 'admin.cities.index' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-city"></i>
                                <p> Cities</p>
                            </a>
                        </li>
                    @endcan

                    @can('amenity.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/amenities') }}" class="nav-link {{ Route::currentRouteName() == 'admin.amenities.index' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-concierge-bell"></i>
                                <p> Amenities</p>
                            </a>
                        </li>
                    @endcan

                    @can('propertytype.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/propertytype') }}" class="nav-link {{ Route::currentRouteName() == 'admin.propertytype.index' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bed"></i>
                                <p> Property Type</p>
                            </a>
                        </li>
                    @endcan
                @endcanany

                @canany(['branch.view', 'property.view'])
                    <li class="nav-header">Inventory</li>
                    @can('branch.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/branches') }}" class="nav-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hotel"></i>
                                <p> Branches</p>
                            </a>
                        </li>
                    @endcan
                    @can('property.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/properties') }}" class="nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p> Properties</p>
                            </a>
                        </li>
                    @endcan
                @endcanany


                @canany(['role.view', 'adminuser.view'])
                    <li class="nav-header">Administration</li>
                    @can('role.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/roles') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                    @endcan

                    @can('adminuser.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/user') }}" class="nav-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Staff</p>
                            </a>
                        </li>
                    @endcan
                @endcanany



                @canany(['offline-booking.view'])
                    <li class="nav-header">BOOKINGS</li>
                    @can('offline-booking.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/offlinebookings') }}" class="nav-link {{ request()->routeIs('admin.offlinebookings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Bookings</p>
                            </a>
                        </li>
                    @endcan               
                @endcanany
                

                @canany(['expense.view'])
                    <li class="nav-header">Finance</li>
                    @can('expense.view')
                        <li class="nav-item">
                            <a href="{{ url('admin/expenses') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p> Expenses</p>
                            </a>
                        </li>
                    @endcan
                    @can('expense.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.report.summaryReport') }}" class="nav-link {{ Route::currentRouteName() == 'admin.report.summaryReport' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p> Reports</p>
                            </a>
                        </li>
                    @endcan
                @endcanany


                
                <li class="nav-header">SYSTEM</li>

                @can('settings.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ Route::currentRouteName() == 'admin.settings.index' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ url('cache-flush') }}" class="nav-link">
                        <i class="nav-icon fas fa-broom"></i>
                        <p>Cache Clear</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>