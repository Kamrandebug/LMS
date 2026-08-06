@extends('admin.layouts.app')

@section('title', 'Add Mock Exam')
@section('page-title', 'Add Mock Exam')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-exams.index') }}">Mock Exams</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle mr-1"></i> New Mock Exam
                </h3>
            </div>
            <form action="{{ route('admin.mock-exams.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @include('admin.mock-exams._form', ['mockExam' => null])
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Create &amp; Add Questions
                    </button>
                    <a href="{{ route('admin.mock-exams.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Workflow</h3>
            </div>
            <div class="card-body">
                <ol class="pl-3 mb-0">
                    <li class="mb-2">
                        <strong>Create the exam</strong><br>
                        <small class="text-muted">Set name, duration, and settings.</small>
                    </li>
                    <li class="mb-2">
                        <strong>Add questions</strong><br>
                        <small class="text-muted">After saving, you'll be taken to the edit page where you can add questions from the question bank.</small>
                    </li>
                    <li>
                        <strong>Activate</strong><br>
                        <small class="text-muted">Toggle Active once the exam is ready for students.</small>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {

    // Name → slug auto-generate
    $('#name').on('input', function () {
        var slug = $(this).val()
            .toLowerCase().trim()
            .replace(/[^a-z0-9\s\-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });

    // Duration → human readable preview
    $('#duration_minutes').on('input', function () {
        var mins = parseInt($(this).val());
        if (!mins || mins < 1) { $('#durationPreview').text(''); return; }
        var h = Math.floor(mins / 60);
        var m = mins % 60;
        var txt = h > 0 ? h + 'h ' : '';
        txt += m > 0 ? m + 'min' : '';
        $('#durationPreview').text('= ' + txt.trim());
    });
    $('#duration_minutes').trigger('input');
});
</script>
@endpush
