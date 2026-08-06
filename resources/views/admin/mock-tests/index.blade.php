@extends('admin.layouts.app')

@section('title', 'Mock Exams')
@section('page-title', 'Mock Exams')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Mock Exams</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
    .duration-badge { font-size: 0.8rem; }
</style>
@endpush

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-alt mr-1"></i>
            All Mock Exams
            <span class="badge badge-secondary ml-1">{{ $mockExams->count() }}</span>
        </h3>
        <div class="card-tools">
            <a href="{{ route('admin.mock-exams.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Mock Exam
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table id="mockExamsTable" class="table table-bordered table-hover mb-0">
            <thead class="thead-dark">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Name</th>
                    <th style="width:100px">Duration</th>
                    <th style="width:100px">Questions</th>
                    <th style="width:80px">Active</th>
                    <th style="width:160px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mockExams as $exam)
                <tr>
                    <td>{{ $exam->id }}</td>
                    <td>
                        <strong>{{ $exam->name }}</strong><br>
                        <code class="small">{{ $exam->slug }}</code>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-secondary duration-badge">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $exam->duration_minutes }} min
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $exam->questions_count > 0 ? 'success' : 'warning' }}">
                            {{ $exam->questions_count }}
                            @if($exam->total_questions)
                                / {{ $exam->total_questions }}
                            @endif
                        </span>
                    </td>
                    <td class="text-center">
                        @if($exam->is_active)
                            <span class="badge badge-success">Yes</span>
                        @else
                            <span class="badge badge-secondary">No</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.mock-exam-questions.index', $exam) }}"
                           class="btn btn-info btn-xs mr-1" title="Manage Questions">
                            <i class="fas fa-list-ol"></i>
                        </a>
                        <a href="{{ route('admin.mock-exams.edit', $exam) }}"
                           class="btn btn-warning btn-xs mr-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-danger btn-xs btn-delete"
                                data-id="{{ $exam->id }}"
                                data-name="{{ addslashes($exam->name) }}"
                                data-count="{{ $exam->questions_count }}"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="deleteForm-{{ $exam->id }}"
                              action="{{ route('admin.mock-exams.destroy', $exam) }}"
                              method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                        No mock exams yet.
                        <a href="{{ route('admin.mock-exams.create') }}">Create the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    $('#mockExamsTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, targets: [5] }]
    });

    $(document).on('click', '.btn-delete', function () {
        var id    = $(this).data('id');
        var name  = $(this).data('name');
        var count = parseInt($(this).data('count'));

        var questionWarning = count > 0
            ? '<br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> This will also remove ' + count + ' question(s) from the exam.</small>'
            : '';

        Swal.fire({
            title: 'Delete Mock Exam?',
            html: 'Delete <b>' + name + '</b> permanently?' + questionWarning + '<br><small class="text-muted">This cannot be undone.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + id).submit();
            }
        });
    });

});
</script>
@endpush
