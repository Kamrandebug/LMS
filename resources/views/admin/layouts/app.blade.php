<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | LearnUp</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
          href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
    <!-- AdminLTE CSS — use exact filename -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min2167.css') }}">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed dark-mode">
<div class="wrapper">

    {{-- ==================== TOP NAVBAR ==================== --}}
    <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ url('/') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt fa-xs"></i> View Site
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="fas fa-user-circle mr-1"></i>{{ auth()->user()->name }}
                </span>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="nav-link btn btn-link text-danger"
                            style="cursor:pointer; border:none; background:none;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
                 alt="LearnUp" class="brand-image img-circle elevation-3" style="opacity:.8">
            <span class="brand-text font-weight-light">LearnUp <b>Admin</b></span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}"
                         class="img-circle elevation-2" alt="Admin">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview" role="menu" data-accordion="false">

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">CONTENT MANAGEMENT</li>

                    {{-- Subjects --}}
                    <li class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Subjects <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Subjects</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.subjects.create') }}" class="nav-link {{ request()->routeIs('admin.subjects.create') ? 'active' : '' }}">
                                    <i class="far fa-plus-square nav-icon"></i>
                                    <p>Add Subject</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Topics --}}
                    <li class="nav-item {{ request()->routeIs('admin.topics.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Topics <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.topics.index') }}" class="nav-link {{ request()->routeIs('admin.topics.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Topics</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.topics.create') }}" class="nav-link {{ request()->routeIs('admin.topics.create') ? 'active' : '' }}">
                                    <i class="far fa-plus-square nav-icon"></i>
                                    <p>Add Topic</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Question Sets --}}
                    <li class="nav-item {{ request()->routeIs('admin.question-sets.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.question-sets.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>Question Sets <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.question-sets.index') }}" class="nav-link {{ request()->routeIs('admin.question-sets.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Sets</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.question-sets.create') }}" class="nav-link {{ request()->routeIs('admin.question-sets.create') ? 'active' : '' }}">
                                    <i class="far fa-plus-square nav-icon"></i>
                                    <p>Add Set</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Questions --}}
                    <li class="nav-item {{ request()->routeIs('admin.questions.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-question-circle"></i>
                            <p>Questions <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.questions.index') }}" class="nav-link {{ request()->routeIs('admin.questions.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Questions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.questions.create') }}" class="nav-link {{ request()->routeIs('admin.questions.create') ? 'active' : '' }}">
                                    <i class="far fa-plus-square nav-icon"></i>
                                    <p>Add Question</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Resource Materials --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.resource-materials.index') }}"
                           class="nav-link {{ request()->routeIs('admin.resource-materials.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Resource Materials</p>
                        </a>
                    </li>

                    {{-- Recorded Lectures --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.recorded-lectures.index') }}"
                           class="nav-link {{ request()->routeIs('admin.recorded-lectures.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Recorded Lectures</p>
                        </a>
                    </li>

                    {{-- Mock Exams --}}
                    <li class="nav-item {{ request()->routeIs('admin.mock-exams.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.mock-exams.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Mock Exams <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.mock-exams.index') }}" class="nav-link {{ request()->routeIs('admin.mock-exams.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Mock Exams</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.mock-exams.create') }}" class="nav-link {{ request()->routeIs('admin.mock-exams.create') ? 'active' : '' }}">
                                    <i class="far fa-plus-square nav-icon"></i>
                                    <p>Add Mock Exam</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">REPORTS & USERS</li>

                    {{-- Question Reports --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-flag text-danger"></i>
                            <p>Question Reports</p>
                        </a>
                    </li>

                    {{-- Users --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>All Users</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- ==================== CONTENT WRAPPER ==================== --}}
    <div class="content-wrapper">

        {{-- Page Header --}}
        @hasSection('content_header')
            @yield('content_header')
        @else
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Admin</a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Main Content --}}
        <section class="content">
            <div class="container-fluid">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')

            </div>
        </section>
    </div>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="main-footer">
        <strong>LearnUp</strong> &mdash; Admin Panel &copy; {{ date('Y') }}
        <div class="float-right d-none d-sm-inline-block">
            <b>AdminLTE</b> v3.2.0
        </div>
    </footer>

    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

{{-- ==================== SCRIPTS ==================== --}}
<!-- jQuery — must load before all others -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 Bundle -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<!-- AdminLTE Core JS — use exact filename -->
<script src="{{ asset('adminlte/dist/js/adminlte.min2167.js') }}"></script>

@stack('scripts')

{{-- Flash Toastr --}}
@if(session('toast_success'))
<script>
    $(function() {
        toastr.success("{{ session('toast_success') }}", "Success");
    });
</script>
@endif
@if(session('toast_error'))
<script>
    $(function() {
        toastr.error("{{ session('toast_error') }}", "Error");
    });
</script>
@endif

</body>
</html>
