@extends('admin.layouts.app')

@section('title', 'Questions — ' . $mockExam->name)
@section('page-title', 'Questions in "' . $mockExam->name . '"')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-exams.index') }}">Mock Exams</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.mock-exams.edit', $mockExam) }}">{{ $mockExam->name }}</a></li>
    <li class="breadcrumb-item active">Questions</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .bank-row:hover { background: #f8f9fa; }
</style>
@endpush

@section('content')

{{-- ===== Single-add hidden form (outside the bulk form) ===== --}}
<form id="singleAddForm" action="{{ route('admin.mock-exam-questions.store', $mockExam) }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="question_id" id="singleQuestionId" value="">
</form>

<div class="row">

    {{-- ===== LEFT: Questions already in the exam ===== --}}
    <div class="col-lg-7 col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-ol mr-1"></i>
                    Questions in This Exam
                    <span class="badge badge-info ml-1">{{ $examQuestions->count() }}</span>
                    @if($mockExam->total_questions)
                        <small class="text-muted">/ {{ $mockExam->total_questions }}</small>
                    @endif
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.mock-exams.edit', $mockExam) }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Exam
                    </a>
                </div>
            </div>

            @if($examQuestions->isEmpty())
                <div class="card-body text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                    <p>No questions in this exam yet.<br>Use the <strong>Add from Question Bank</strong> panel to add questions.</p>
                </div>
            @else
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:80px">Order</th>
                            <th>Question</th>
                            <th style="width:130px">Set</th>
                            <th style="width:60px">Answer</th>
                            <th style="width:80px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examQuestions as $eq)
                        <tr>
                            <td class="text-center align-middle">
                                <form class="order-form"
                                      action="{{ route('admin.mock-exam-questions.update-order', [$mockExam, $eq]) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number"
                                           name="sort_order"
                                           value="{{ $eq->sort_order }}"
                                           min="0"
                                           class="form-control form-control-sm order-input text-center"
                                           style="width:64px; display:inline-block;">
                                </form>
                            </td>
                            <td class="align-middle">
                                <div>{{ $eq->question_text }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-quote-left mr-1"></i>
                                    {{ \Illuminate\Support\Str::limit($eq->{'option_' . $eq->correct_option}, 60) }}
                                </small>
                            </td>
                            <td class="align-middle small text-muted">
                                {{ $eq->questionSet->name ?? '—' }}
                                @if($eq->questionSet && $eq->questionSet->topic)
                                    <div class="text-muted">{{ $eq->questionSet->topic->name }}</div>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-success" title="{{ $eq->{'option_' . $eq->correct_option} }}">
                                    {{ strtoupper($eq->correct_option) }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove"
                                        data-id="{{ $eq->id }}"
                                        data-question="{{ addslashes($eq->question_text) }}"
                                        title="Remove from exam">
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                                <form id="removeForm-{{ $eq->id }}"
                                      action="{{ route('admin.mock-exam-questions.destroy', [$mockExam, $eq]) }}"
                                      method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-sm" id="saveAllOrders">
                    <i class="fas fa-save mr-1"></i> Save Order Changes
                </button>
                <small class="text-muted ml-2">Lower numbers appear first.</small>
            </div>
            @endif
        </div>
    </div>

    {{-- ===== RIGHT: Add from Question Bank ===== --}}
    <div class="col-lg-5 col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database mr-1"></i>
                    Add from Question Bank
                    <span class="badge badge-success ml-1">{{ $bankQuestions->count() }}</span>
                </h3>
            </div>

            {{-- Filters --}}
            <div class="card-body py-2">
                <form method="GET" action="{{ route('admin.mock-exam-questions.index', $mockExam) }}" id="bankFilterForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="text-muted small mb-1">Subject</label>
                                <select name="subject_id" id="bankSubject" class="form-control select2 select2-bootstrap4">
                                    <option value="">— All Subjects —</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                            {!! $subject->icon_svg ?? '' !!} {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="text-muted small mb-1">Topic</label>
                                <select name="topic_id" id="bankTopic" class="form-control select2 select2-bootstrap4">
                                    <option value="">— All Topics —</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}"
                                                data-subject="{{ $topic->subject_id }}"
                                            {{ $selectedTopicId == $topic->id ? 'selected' : '' }}>
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="text-muted small mb-1">Question Set</label>
                                <select name="question_set_id" id="bankSet" class="form-control select2 select2-bootstrap4">
                                    <option value="">— All Sets —</option>
                                    @foreach($questionSets as $set)
                                        <option value="{{ $set->id }}"
                                                data-topic="{{ $set->topic_id }}"
                                            {{ $selectedSetId == $set->id ? 'selected' : '' }}>
                                            {{ $set->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search mr-1"></i> Apply Filter
                            </button>
                            <a href="{{ route('admin.mock-exam-questions.index', $mockExam) }}" class="btn btn-sm btn-secondary">
                                Clear
                            </a>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>Added questions are excluded.
                        </small>
                    </div>
                </form>
            </div>

            {{-- Bulk add form wrapping the bank table --}}
            <form id="bulkAddForm" action="{{ route('admin.mock-exam-questions.bulk-store', $mockExam) }}" method="POST">
                @csrf
                <div class="card-body p-0">
                    @if($bankQuestions->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                            <p>No available questions.</p>
                            <small>All bank questions may already be in this exam, or the filter returned no results.</small>
                        </div>
                    @else
                    <table id="bankTable" class="table table-bordered table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width:36px"><input type="checkbox" id="selectAllBank" title="Select all"></th>
                                <th>Question</th>
                                <th style="width:110px">Set</th>
                                <th style="width:50px">Ans</th>
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bankQuestions as $q)
                            <tr class="bank-row">
                                <td class="text-center align-middle">
                                    <input type="checkbox" class="bank-checkbox" name="question_ids[]" value="{{ $q->id }}">
                                </td>
                                <td class="align-middle">
                                    <div>{{ $q->question }}</div>
                                    @if($q->qid)
                                        <code class="small">{{ $q->qid }}</code>
                                    @endif
                                    <small class="text-muted d-block">
                                        {{ $q->questionSet->topic->subject->name ?? '—' }}
                                        @if($q->questionSet && $q->questionSet->topic)
                                            / {{ $q->questionSet->topic->name }}
                                        @endif
                                    </small>
                                </td>
                                <td class="align-middle small text-muted">{{ $q->questionSet->name ?? '—' }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success" title="{{ $q->correct_answer_text }}">
                                        {{ strtoupper($q->correct_option) }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button"
                                            class="btn btn-success btn-xs btn-add-bank"
                                            data-id="{{ $q->id }}"
                                            title="Add to exam">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-sm" id="bulkAddBtn">
                            <i class="fas fa-plus mr-1"></i> Add Selected
                        </button>
                        <span class="text-muted small ml-2" id="bankSelectedCount"></span>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    $('.select2').select2({ theme: 'bootstrap4' });

    // ===== Bank filter cascade: Subject → Topic → Set =====
    var allTopics = @json($topics->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'subject_id' => $t->subject_id]));
    var allSets   = @json($questionSets->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'topic_id' => $s->topic_id]));

    function rebuildTopics(subjectId, keepVal) {
        var $sel = $('#bankTopic');
        $sel.empty().append('<option value="">— All Topics —</option>');
        $.each(allTopics, function (_, t) {
            if (!subjectId || t.subject_id == subjectId) {
                $sel.append($('<option>', { value: t.id, text: t.name }));
            }
        });
        if (keepVal) $sel.val(keepVal).trigger('change.select2');
    }

    function rebuildSets(topicId, keepVal) {
        var $sel = $('#bankSet');
        $sel.empty().append('<option value="">— All Sets —</option>');
        $.each(allSets, function (_, s) {
            if (!topicId || s.topic_id == topicId) {
                $sel.append($('<option>', { value: s.id, text: s.name }));
            }
        });
        if (keepVal) $sel.val(keepVal).trigger('change.select2');
    }

    $('#bankSubject').on('change', function () {
        rebuildTopics($(this).val(), null);
        rebuildSets(null, null);
    });
    $('#bankTopic').on('change', function () {
        rebuildSets($(this).val(), null);
    });

    // Restore cascades on page load
    @if($selectedSubjectId)
        rebuildTopics({{ $selectedSubjectId }}, {{ $selectedTopicId ?? 'null' }});
    @endif
    @if($selectedTopicId)
        rebuildSets({{ $selectedTopicId }}, {{ $selectedSetId ?? 'null' }});
    @endif

    // Bank table DataTables
    var bankTable = $('#bankTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 15,
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 3, 4] }
        ],
        language: { emptyTable: 'No available questions.' }
    });

    // Select all checkboxes
    $('#selectAllBank').on('change', function () {
        $('.bank-checkbox').prop('checked', $(this).is(':checked'));
        updateSelectedCount();
    });

    // Live count of selected rows
    $(document).on('change', '.bank-checkbox', updateSelectedCount);
    function updateSelectedCount() {
        var n = $('.bank-checkbox:checked').length;
        $('#bankSelectedCount').text(n > 0 ? n + ' selected' : '');
    }

    // Bulk add — guard: at least 1 selected
    $('#bulkAddBtn').on('click', function (e) {
        var checked = $('.bank-checkbox:checked').length;
        if (checked === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Nothing selected',
                text: 'Please select at least one question to add.',
                confirmButtonText: 'OK',
            });
        }
    });

    // Single add (per-row +)
    $(document).on('click', '.btn-add-bank', function () {
        $('#singleQuestionId').val($(this).data('id'));
        $('#singleAddForm').submit();
    });

    // Remove from exam
    $(document).on('click', '.btn-remove', function () {
        var id  = $(this).data('id');
        var txt = $(this).data('question');
        Swal.fire({
            title: 'Remove from Exam?',
            html: '"<em>' + txt + '</em>"<br><small class="text-muted">The question stays in the question bank — it is only removed from this exam.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Remove',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('removeForm-' + id).submit();
            }
        });
    });

    // Save all order changes — submit all order forms in sequence
    $('#saveAllOrders').on('click', function () {
        var forms = $('.order-form');
        if (forms.length === 0) return;

        var promises = [];
        forms.each(function () {
            var formData = $(this).serialize();
            var action   = $(this).attr('action');
            promises.push(
                $.ajax({
                    url: action,
                    method: 'POST',
                    data: formData,
                    error: function () {} // expected redirect; ignore
                })
            );
        });

        if (Promise.allSettled) {
            Promise.allSettled(promises).then(function () {
                window.location.reload();
            });
        } else {
            setTimeout(function () { window.location.reload(); }, promises.length * 300);
        }
    });

});
</script>
@endpush
