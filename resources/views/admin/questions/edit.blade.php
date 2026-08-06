@extends('admin.layouts.app')

@section('title', 'Edit Question')
@section('page-title', 'Edit Question')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
    <li class="breadcrumb-item active">Edit #{{ $question->id }}</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-lg-9 col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Question #{{ $question->id }}
                </h3>
            </div>
            <form action="{{ route('admin.questions.update', $question) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('admin.questions._form', [
                        'question'        => $question,
                        'selectedSubject' => null,
                        'selectedTopic'   => null,
                        'selectedSet'     => null,
                    ])
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Question
                        </button>
                        <a href="{{ route('admin.questions.index', ['question_set_id' => $question->question_set_id]) }}"
                           class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <small class="text-muted mr-3">
                            ID: {{ $question->id }} &nbsp;|&nbsp;
                            Created: {{ $question->created_at->format('d M Y') }}
                        </small>
                        <form action="{{ route('admin.questions.destroy', $question) }}"
                              method="POST" id="deleteQuestionForm" class="mb-0">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm" id="deleteQuestionBtn">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-3 col-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Question Info</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Subject</td>
                        <td>
                            <span class="badge badge-info">
                                {!! $question->questionSet->topic->subject->icon_svg ?? '' !!}
                                {{ $question->questionSet->topic->subject->name ?? '—' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Topic</td>
                        <td>{{ $question->questionSet->topic->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Set</td>
                        <td>{{ $question->questionSet->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Correct</td>
                        <td>
                            <span class="badge badge-success font-weight-bold">
                                {{ strtoupper($question->correct_option) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($question->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function() {
    $('#deleteQuestionBtn').on('click', function() {
        Swal.fire({
            title: 'Delete Question?',
            html: 'Delete question <b>#{{ $question->id }}</b> permanently?<br><small class="text-muted">This cannot be undone.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('deleteQuestionForm').submit();
            }
        });
    });
});
</script>
@endpush
