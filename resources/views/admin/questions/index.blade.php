@extends('admin.layouts.app')

@section('title', 'Questions')
@section('page-title', 'Questions')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Questions</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .correct-badge { font-size: 0.85rem; font-weight: 700; padding: 4px 10px; }
    .option-preview { font-size: 0.82rem; color: #6c757d; }
    .correct-option-text { color: #28a745; font-weight: 600; }
</style>
@endpush

@section('content')

{{-- Filter Card --}}
<div class="card card-outline card-secondary mb-3">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.questions.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="text-muted small mb-1">Subject</label>
                        <select name="subject_id" id="filterSubject" class="form-control select2 select2-bootstrap4">
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
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="text-muted small mb-1">Topic</label>
                        <select name="topic_id" id="filterTopic" class="form-control select2 select2-bootstrap4">
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
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="text-muted small mb-1">Question Set</label>
                        <select name="question_set_id" id="filterSet" class="form-control select2 select2-bootstrap4">
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
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Main Card --}}
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-question-circle mr-1"></i>
            Questions
            @if($selectedSetId || $selectedTopicId || $selectedSubjectId)
                <span class="badge badge-info ml-2">Filtered</span>
            @endif
            <span class="badge badge-secondary ml-1">{{ $questions->count() }}</span>
        </h3>
        <div class="card-tools">
            <a href="{{ route('admin.questions.create', array_filter([
                'subject_id'      => $selectedSubjectId,
                'topic_id'        => $selectedTopicId,
                'question_set_id' => $selectedSetId,
            ])) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Question
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table id="questionsTable" class="table table-bordered table-hover mb-0">
            <thead class="thead-dark">
                <tr>
                    <th style="width:50px">#</th>
                    <th style="width:80px">QID</th>
                    <th>Question</th>
                    <th style="width:130px">Set</th>
                    <th style="width:110px">Correct Ans</th>
                    <th style="width:70px">Order</th>
                    <th style="width:70px">Active</th>
                    <th style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                <tr>
                    <td>{{ $q->id }}</td>
                    <td><small class="text-muted">{{ $q->qid ?? '—' }}</small></td>
                    <td>
                        <div class="font-weight-bold text-truncate" style="max-width:420px;">
                            {{ \Illuminate\Support\Str::limit($q->question, 100) }}
                        </div>
                        <div class="option-preview mt-1">
                            <span class="{{ $q->correct_option === 'a' ? 'correct-option-text' : '' }}">A: {{ \Illuminate\Support\Str::limit($q->option_a, 40) }}</span>
                            &nbsp;|&nbsp;
                            <span class="{{ $q->correct_option === 'b' ? 'correct-option-text' : '' }}">B: {{ \Illuminate\Support\Str::limit($q->option_b, 40) }}</span>
                            &nbsp;|&nbsp;
                            <span class="{{ $q->correct_option === 'c' ? 'correct-option-text' : '' }}">C: {{ \Illuminate\Support\Str::limit($q->option_c, 40) }}</span>
                            &nbsp;|&nbsp;
                            <span class="{{ $q->correct_option === 'd' ? 'correct-option-text' : '' }}">D: {{ \Illuminate\Support\Str::limit($q->option_d, 40) }}</span>
                        </div>
                        <small class="text-muted">
                            {!! $q->questionSet->topic->subject->icon_svg ?? '' !!}
                            {{ $q->questionSet->topic->subject->name ?? '' }}
                            &rsaquo; {{ $q->questionSet->topic->name ?? '' }}
                            &rsaquo; {{ $q->questionSet->name ?? '' }}
                        </small>
                    </td>
                    <td>
                        <small>{{ $q->questionSet->name ?? '—' }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success correct-badge">
                            {{ strtoupper($q->correct_option) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $q->sort_order ?? '—' }}</td>
                    <td class="text-center">
                        @if($q->is_active)
                            <span class="badge badge-success">Yes</span>
                        @else
                            <span class="badge badge-secondary">No</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.questions.edit', $q) }}"
                           class="btn btn-warning btn-xs mr-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-danger btn-xs btn-delete"
                                data-id="{{ $q->id }}"
                                data-question="{{ \Illuminate\Support\Str::limit(addslashes($q->question), 60) }}"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="deleteForm-{{ $q->id }}"
                              action="{{ route('admin.questions.destroy', $q) }}"
                              method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-question-circle fa-2x mb-2 d-block"></i>
                        No questions found.
                        <a href="{{ route('admin.questions.create') }}">Add the first one.</a>
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
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    $('#questionsTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [{ orderable: false, targets: [7] }]
    });

    $('.select2').select2({ theme: 'bootstrap4' });

    var allTopics = @json($topics->map(fn($t) => ['id'=>$t->id,'name'=>$t->name,'subject_id'=>$t->subject_id]));
    var allSets   = @json($questionSets->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'topic_id'=>$s->topic_id]));

    function rebuildTopics(subjectId, keepTopic) {
        var $sel = $('#filterTopic');
        $sel.empty().append('<option value="">— All Topics —</option>');
        $.each(allTopics, function(_, t) {
            if (!subjectId || t.subject_id == subjectId) {
                $sel.append($('<option>', {value: t.id, text: t.name}));
            }
        });
        if (keepTopic) $sel.val(keepTopic).trigger('change.select2');
    }

    function rebuildSets(topicId, keepSet) {
        var $sel = $('#filterSet');
        $sel.empty().append('<option value="">— All Sets —</option>');
        $.each(allSets, function(_, s) {
            if (!topicId || s.topic_id == topicId) {
                $sel.append($('<option>', {value: s.id, text: s.name}));
            }
        });
        if (keepSet) $sel.val(keepSet).trigger('change.select2');
    }

    $('#filterSubject').on('change', function() {
        rebuildTopics($(this).val(), null);
        rebuildSets(null, null);
    });

    $('#filterTopic').on('change', function() {
        rebuildSets($(this).val(), null);
    });

    // Restore filters on page load
    @if($selectedSubjectId)
        rebuildTopics({{ $selectedSubjectId }}, {{ $selectedTopicId ?? 'null' }});
    @endif
    @if($selectedTopicId)
        rebuildSets({{ $selectedTopicId }}, {{ $selectedSetId ?? 'null' }});
    @endif

    // Delete
    $(document).on('click', '.btn-delete', function() {
        var id  = $(this).data('id');
        var txt = $(this).data('question');
        Swal.fire({
            title: 'Delete Question?',
            html: '"<em>' + txt + '</em>"<br><small class="text-muted">This cannot be undone.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + id).submit();
            }
        });
    });

});
</script>
@endpush
