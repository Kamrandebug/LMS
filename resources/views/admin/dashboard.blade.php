@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- ===== ROW 1: Primary Stats ===== --}}
<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['subjects'] }}</h3>
                <p>Total Subjects</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
            <a href="{{ route('admin.subjects.index') }}" class="small-box-footer">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['question_sets'] }}</h3>
                <p>Question Sets</p>
            </div>
            <div class="icon"><i class="fas fa-layer-group"></i></div>
            <a href="{{ route('admin.question-sets.index') }}" class="small-box-footer">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['questions'] }}</h3>
                <p>Total Questions</p>
            </div>
            <div class="icon"><i class="fas fa-question-circle"></i></div>
            <a href="{{ route('admin.questions.index') }}" class="small-box-footer">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['mock_exams'] }}</h3>
                <p>Mock Exams</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="{{ route('admin.mock-exams.index') }}" class="small-box-footer">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

{{-- ===== ROW 2: Secondary Stats ===== --}}
<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background:#6f42c1;">
            <div class="inner" style="color:#fff;">
                <h3>{{ $stats['topics'] }}</h3>
                <p>Total Topics</p>
            </div>
            <div class="icon" style="color:rgba(255,255,255,.3);">
                <i class="fas fa-list"></i>
            </div>
            <a href="{{ route('admin.topics.index') }}" class="small-box-footer"
               style="color:#fff; background:rgba(0,0,0,.15);">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background:#20c997;">
            <div class="inner" style="color:#fff;">
                <h3>{{ $stats['mock_questions'] }}</h3>
                <p>Mock Exam Questions</p>
            </div>
            <div class="icon" style="color:rgba(255,255,255,.3);">
                <i class="fas fa-tasks"></i>
            </div>
            <a href="#" class="small-box-footer"
               style="color:#fff; background:rgba(0,0,0,.15);">
                Manage <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        @php $reportClass = $stats['pending_reports'] > 0 ? 'bg-danger' : 'bg-secondary'; @endphp
        <div class="small-box {{ $reportClass }}">
            <div class="inner">
                <h3>{{ $stats['pending_reports'] }}</h3>
                <p>Pending Reports</p>
            </div>
            <div class="icon"><i class="fas fa-flag"></i></div>
            <a href="{{ route('admin.reports.index') }}" class="small-box-footer">
                Review <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box" style="background:#fd7e14;">
            <div class="inner" style="color:#fff;">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Registered Users</p>
            </div>
            <div class="icon" style="color:rgba(255,255,255,.3);">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer"
               style="color:#fff; background:rgba(0,0,0,.15);">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

{{-- ===== ROW 3: Quick Actions Card ===== --}}
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> Add Subject
                </a>
                <a href="{{ route('admin.topics.create') }}" class="btn btn-primary mr-2 mb-2" style="background:#6f42c1; border-color:#6f42c1;">
                    <i class="fas fa-plus mr-1"></i> Add Topic
                </a>
                <a href="{{ route('admin.questions.create') }}" class="btn btn-success mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> Add Question
                </a>
                <a href="{{ route('admin.mock-exams.create') }}" class="btn btn-warning mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> Add Mock Exam
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-danger mr-2 mb-2">
                    <i class="fas fa-flag mr-1"></i>
                    View Reports
                    @if($stats['pending_reports'] > 0)
                        <span class="badge badge-light ml-1">{{ $stats['pending_reports'] }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
