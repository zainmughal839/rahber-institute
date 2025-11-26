@php
use Illuminate\Support\Facades\Route;
$currentRoute = Route::currentRouteName();
@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-text fw-light">RAHBER INSTITUTE</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" id="navigation">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">Combination Session & Program</li>

                <!-- Session & Program Dropdown -->
                <!-- Session & Program Dropdown -->
                <li class="nav-item {{ str_starts_with($currentRoute, 'sessions.') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Session & Program <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        @can('session.view')
                        <li class="nav-item">
                            <a href="{{ route('sessions.index') }}"
                                class="nav-link {{ in_array($currentRoute, ['sessions.index', 'sessions.all']) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>All Sessions</p>
                            </a>
                        </li>
                        @endcan

                        @can('session.create')
                        <li class="nav-item">
                            <a href="{{ route('sessions.create') }}"
                                class="nav-link {{ $currentRoute === 'sessions.create' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Add Session</p>
                            </a>
                        </li>
                        @endcan

                        <!-- Programs (abhi open rakh sakte ho - baad mein permission laga dena) -->
                        <li class="nav-item">
                            <a href="{{ route('programs.index') }}"
                                class="nav-link {{ str_starts_with($currentRoute, 'programs.') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>All Programs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('programs.create') }}"
                                class="nav-link {{ $currentRoute === 'programs.create' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Add Program</p>
                            </a>
                        </li>

                        <!-- Session Program Assignment -->
                        <li class="nav-item">
                            <a href="{{ route('session_program.index') }}"
                                class="nav-link {{ str_starts_with($currentRoute, 'session_program.') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>All Session-Program</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- resources/views/layout/sidebar.blade.php -->
                <li class="nav-header">Role & Permission</li>
                <li class="nav-item {{ request()->is('users*', 'roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('users*', 'roles*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-lock"></i>
                        <p>
                            Role & Permission
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Users -->
                        <li class="nav-item {{ request()->is('users*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>
                                    Users
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}"
                                        class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Add User</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>All Users</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Roles -->
                        <li class="nav-item {{ request()->is('roles*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-key-fill"></i>
                                <p>
                                    Roles
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('roles.create') }}"
                                        class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Create Role</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>All Roles</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">Settings</li>
                <!-- Profile Setting -->
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ $currentRoute === 'profile.edit' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Profile Setting</p>
                    </a>
                </li>

                <!-- (Baqi examples jo static hain unko chhor sakte ho ya same tareeke se active kar sakte ho) -->

            </ul>
        </nav>
    </div>
</aside>