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

                @canany(['session.index', 'session.create', 'program.index', 'program.create', 'session_program.index'])

                <li class="nav-header">Combination Session & Program</li>


                {{-- SESSION & PROGRAM MENU --}}
                <li
                    class="nav-item {{ str_starts_with($currentRoute, 'sessions.') || str_starts_with($currentRoute, 'programs.')  || str_starts_with($currentRoute, 'session_program.') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Session & Program <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        @can('session.index')
                        <li class="nav-item">
                            <a href="{{ route('sessions.index') }}"
                                class="nav-link {{ request()->routeIs('sessions.index') ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>All Sessions</p>
                            </a>
                        </li>
                        @endcan

                        @can('session.create')
                        <li class="nav-item">
                            <a href="{{ route('sessions.create') }}"
                                class="nav-link {{ request()->routeIs('sessions.create') ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Add Session</p>
                            </a>
                        </li>
                        @endcan

                        {{-- Programs --}}
                        @can('program.index')
                        <li class="nav-item">
                            <a href="{{ route('programs.index') }}"
                                class="nav-link {{ request()->routeIs('programs.index') ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>All Programs</p>
                            </a>
                        </li>
                        @endcan

                        @can('program.create')
                        <li class="nav-item">
                            <a href="{{ route('programs.create') }}"
                                class="nav-link {{ request()->routeIs('programs.create') ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Add Program</p>
                            </a>
                        </li>
                        @endcan

                        @can('session_program.index')
                        <li class="nav-item">
                            <a href="{{ route('session_program.index') }}"
                                class="nav-link  {{ request()->routeIs('session_program.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>All Session-Program</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan




                @canany(['role.index', 'role.create', 'user.index', 'user.create'])
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
                        @canany(['user.index', 'user.create'])
                        <li class="nav-item {{ request()->is('users*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>
                                    Users
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                @can('user.index')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        <i class="bi bi-people nav-icon"></i>
                                        <p>All Users</p>
                                    </a>
                                </li>
                                @endcan

                                @can('user.create')
                                <li class="nav-item">
                                    <a href="{{ route('users.create') }}" class="nav-link">
                                        <i class="bi bi-circle nav-icon"></i>
                                        <p>Add User</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        <!-- Roles -->
                        @canany(['role.index', 'role.create'])
                        <li class="nav-item {{ request()->is('roles*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-key-fill"></i>
                                <p>
                                    Roles
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">
                                @can('role.index')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link">
                                        <i class="bi bi-key nav-icon"></i>
                                        <p>All Roles</p>
                                    </a>
                                </li>
                                @endcan

                                @can('role.create')
                                <li class="nav-item">
                                    <a href="{{ route('roles.create') }}" class="nav-link">
                                        <i class="bi bi-circle nav-icon"></i>
                                        <p>Create Role</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan


                @canany(['student.index', 'student.create'])
                <li class="nav-header">Student & Teacher</li>
                <li class="nav-item {{ str_starts_with($currentRoute, 'students.') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Student & Teacher <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>


                    <ul class="nav nav-treeview">
                        <!-- Student Fee List -->
                        @can('student.create')
                        <li class="nav-item">
                            <a href="{{ route('students.create') }}"
                                class="nav-link {{ $currentRoute === 'students.create' ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Add Student</p>
                            </a>
                        </li>
                        @endcan

                        @can('student.index')
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}"
                                class="nav-link {{ $currentRoute === 'students.index' ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>All Student</p>
                            </a>
                        </li>
                        @endcan


                    </ul>
                </li>
                @endcan

                <li class="nav-header">Settings</li>
                <!-- Profile Setting -->
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ $currentRoute === 'profile.edit' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Profile Setting</p>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>