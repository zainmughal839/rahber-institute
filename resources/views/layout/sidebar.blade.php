@php
use Illuminate\Support\Facades\Route;
$currentRoute = Route::currentRouteName();
@endphp

@php
$assignment = auth()->check() ? auth()->user()->userAssignment : null;
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

                @canany(['student.index', 'student.create', 'teacher.index', 'teacher.create', 'stu_category.index',
                'stu_category.create'])
                <li class="nav-header">Student & Teacher</li>
                <li
                    class="nav-item {{ str_starts_with($currentRoute, 'students.') || str_starts_with($currentRoute, 'teachers.') || str_starts_with($currentRoute, 'stu-category.') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Student & Teacher <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        @can('stu_category.index')
                        <li class="nav-item">
                            <a href="{{ route('stu-category.index') }}"
                                class="nav-link {{ $currentRoute === 'stu-category.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-collection"></i>
                                <p>Student Category</p>
                            </a>
                        </li>
                        @endcan

                        {{-- @if(!$userAssignment || $userAssignment->panel_type === 'student') --}}
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

                        @can('student.index')
                        <li class="nav-item">
                            <a href="{{ route('students.ledger.all') }}"
                                class="nav-link {{ $currentRoute === 'students.ledger.all' ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Student Ledger</p>
                            </a>
                        </li>
                        @endcan
                        {{-- @endif--}}

                        @can('teacher.create')
                        <li class="nav-item">
                            <a href="{{ route('teachers.create') }}"
                                class="nav-link {{ $currentRoute === 'teachers.create' ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Add Teacher</p>
                            </a>
                        </li>
                        @endcan

                        @can('teacher.index')
                        <li class="nav-item">
                            <a href="{{ route('teachers.index') }}"
                                class="nav-link {{ $currentRoute === 'teachers.index' ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>All Teacher</p>
                            </a>
                        </li>
                        @endcan

                        @canany('teacher.index')
                        <li class="nav-item">
                            <a href="{{ route('teachers.all_ledger') }}"
                                class="nav-link {{ $currentRoute === 'teachers.all_ledger' ? 'active' : '' }}">
                                <i class="bi bi-circle nav-icon"></i>
                                <p>Teachers Ledger</p>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan

                @canany(['subject.index', 'subject.create', 'class-subject.index', 'class-subject.create',
                'class-teacher.index',
                'class-teacher.create'])
                <li class="nav-header">Teacher classes</li>
                <li
                    class="nav-item {{ str_starts_with($currentRoute, 'subjects.') || str_starts_with($currentRoute, 'class-subjects.') || str_starts_with($currentRoute, 'class-teacher.') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Teacher Assign class <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        @can('subject.index')
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}"
                                class="nav-link {{ $currentRoute === 'subjects.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Books / Subjects</p>
                            </a>
                        </li>
                        @endcan

                        @can('class-subject.index')
                        <li class="nav-item">
                            <a href="{{ route('class-subjects.index') }}"
                                class="nav-link {{ $currentRoute === 'class-subjects.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Subject assign Session</p>
                            </a>
                        </li>
                        @endcan

                        @can('class-teacher.index')
                        <li class="nav-item">
                            <a href="{{ route('class-teacher.index') }}"
                                class="nav-link {{ $currentRoute === 'class-teacher.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Class Assign Teacher</p>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan

                @canany(['task-cat.index', 'task-cat.create', 'task.index', 'task.create', 'announcement.create', 'announcement.index'])
                <li class="nav-header">Task, Announcement </li>
                <li
                    class="nav-item {{ str_starts_with($currentRoute, 'task-cat.') || str_starts_with($currentRoute, 'tasks.') || str_starts_with($currentRoute, 'announcements.') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-list-task"></i>
                        <p>Task, Announcement<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        @can('task-cat.index')
                        <li class="nav-item">
                            <a href="{{ route('task-cat.index') }}"
                                class="nav-link {{ $currentRoute === 'task-cat.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Task Categories</p>
                            </a>
                        </li>
                        @endcan

                        @can('task.index')
                        <li class="nav-item">
                            <a href="{{ route('tasks.index') }}"
                                class="nav-link {{ $currentRoute === 'tasks.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Task Assign</p>
                            </a>
                        </li>
                        @endcan

                        @can('announcement.index')
                        <li class="nav-item">
                            <a href="{{ route('announcements.index') }}"
                                class="nav-link {{ $currentRoute === 'announcements.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Announcements</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @canany(['role.index', 'role.create', 'user.index', 'user.create', 'user-assignment.index',
                'user-assignment.create'])
                <li class="nav-header">Role & Permission</li>

                <li class="nav-item {{ request()->is('users*', 'roles*', 'user-assignment*')  ? 'menu-open' : '' }}">
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

                        @can('user-assignment.index')
                        <li class="nav-item">
                            <a href="{{ route('user-assignments.index') }}"
                                class="nav-link {{ $currentRoute === 'user-assignments.index' ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Panel Assignments</p>
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