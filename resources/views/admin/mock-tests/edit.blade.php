@extends('admin.layouts.app')

@section('title', 'Edit Mock Exam')
@section('page-title', 'Edit Mock Exam')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-exams.index') }}">Mock Exams</a></li>
    <li class="breadcrumb-item active">Edit: {{ $mockExam->name }}</li>
@endsection

@section('content')
<div class="row">

    {{-- Left: Edit Form --}}
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i> Edit: {{ $mockExam->name }}
                </h3>
            </div>
            <form action="{{ route('admin.mock-exams.update', $mockExam) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('admin.mock-exams._form', ['mockExam' => $mockExam])
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Exam
                        </button>
                        <a href="{{ route('admin.mock-exams.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    <small class="text-muted">
                        ID: {{ $mockExam->id }} &nbsp;|&nbsp;
                        Created: {{ $mockExam->created_at->format('d M Y') }}
                    </small>
                </div>
            </form>
        </div>

        {{-- Questions placeholder — next prompt will replace this --}}
        <div class="card card-outline card-info" id="questions">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-ol mr-1"></i>
                    Questions in This Exam
                    <span class="badge badge-{{ $mockExam->questions_count > 0 ? 'success' : 'warning' }} ml-1">
                        {{ $mockExam->questions_count }}
                    </span>
                </h3>
            </div>
            <div class="card-body text-center py-3">
                <p class="text-muted mb-2">Manage which questions appear in this exam.</p>
                <a href="{{ route('admin.mock-exam-questions.index', $mockExam) }}"
                   class="btn btn-info">
                    <i class="fas fa-list-ol mr-1"></i> Manage Questions
                </a>
            </div>
        </div>
    </div>

    {{-- Right: Stats + Danger --}}
    <div class="col-lg-4 col-12">

        {{-- Stats Card --}}
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Exam Stats</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Questions Added</td>
                        <td>
                            <span class="badge badge-{{ $mockExam->questions_count > 0 ? 'success' : 'warning' }}">
                                {{ $mockExam->questions_count }}
                                @if($mockExam->total_questions)
                                    / {{ $mockExam->total_questions }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Duration</td>
                        <td>
                            <span class="badge badge-secondary">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $mockExam->duration_minutes }} min
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($mockExam->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Slug</td>
                        <td><code class="small">{{ $mockExam->slug }}</code></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title text-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Danger Zone
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Deleting this exam will also remove all
                    <strong>{{ $mockExam->questions_count }} question(s)</strong>
                    from it. The questions themselves will NOT be deleted from the question bank.
                </p>
                <form action="{{ route('admin.mock-exams.destroy', $mockExam) }}"
                      method="POST" id="deleteExamForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm" id="deleteExamBtn"
                            data-name="{{ addslashes($mockExam->name) }}"
                            data-count="{{ $mockExam->questions_count }}">
                        <i class="fas fa-trash mr-1"></i> Delete This Exam
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    // Re-generate slug button
    $('#regenSlugBtn').on('click', function () {
        var slug = $('#name').val()
            .toLowerCase().trim()
            .replace(/[^a-z0-9\s\-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });

    // Duration preview
    $('#duration_minutes').on('input', function () {
        var mins = parseInt($(this).val());
        if (!mins || mins < 1) { $('#durationPreview').text(''); return; }
        var h = Math.floor(mins / 60), m = mins % 60;
        var txt = (h > 0 ? h + 'h ' : '') + (m > 0 ? m + 'min' : '');
        $('#durationPreview').text('= ' + txt.trim());
    });
    $('#duration_minutes').trigger('input');

    // Delete SweetAlert
    $('#deleteExamBtn').on('click', function () {
        var name  = $(this).data('name');
        var count = parseInt($(this).data('count'));
        var warn  = count > 0
            ? '<br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> ' + count + ' question link(s) will be removed from the exam (questions stay in the bank).</small>'
            : '';
        Swal.fire({
            title: 'Delete Mock Exam?',
            html: 'Delete <b>' + name + '</b> permanently?' + warn,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('deleteExamForm').submit();
            }
        });
    });
});
</script>
@endpush
