@extends('admin.layouts.app')

@section('title', 'Question Reports')
@section('page-title', 'Question Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Question Reports</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .row-pending { background-color: #fff8e1 !important; }
</style>
@endpush

@section('content')

{{-- ===== Summary Badges ===== --}}
<div class="row mb-3">
    <div class="col-lg-4 col-6">
        <div class="small-box {{ $selectedStatus === 'pending' ? 'bg-warning' : 'bg-gradient-warning' }}">
            <div class="inner">
                <h3>{{ $pendingCount }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="small-box-footer">
                View Pending <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box {{ $selectedStatus === 'reviewed' ? 'bg-secondary' : 'bg-gradient-secondary' }}">
            <div class="inner">
                <h3>{{ $reviewedCount }}</h3>
                <p>Reviewed</p>
            </div>
            <div class="icon"><i class="fas fa-check-double"></i></div>
            <a href="{{ route('admin.reports.index', ['status' => 'reviewed']) }}" class="small-box-footer">
                View Reviewed <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box {{ $selectedStatus === 'resolved' ? 'bg-success' : 'bg-gradient-success' }}">
            <div class="inner">
                <h3>{{ $resolvedCount }}</h3>
                <p>Resolved</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" class="small-box-footer">
                View Resolved <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- ===== Filter Card ===== --}}
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
        <form method="GET" action="{{ route('admin.reports.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="text-muted small mb-1">Status</label>
                        <select name="status" class="form-control select2 select2-bootstrap4">
                            <option value="">— All Statuses —</option>
                            <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $selectedStatus === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="resolved" {{ $selectedStatus === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>
                </div>
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
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm ml-1">
                        <i class="fas fa-times mr-1"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== Bulk action forms (hidden, populated by JS) ===== --}}
<form id="bulkResolveForm" action="{{ route('admin.reports.bulk-resolve') }}" method="POST" style="display:none;">
    @csrf
    <div id="bulkResolveIds"></div>
</form>
<form id="bulkDismissForm" action="{{ route('admin.reports.bulk-dismiss') }}" method="POST" style="display:none;">
    @csrf
    <div id="bulkDismissIds"></div>
</form>

{{-- ===== Reports Table ===== --}}
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-flag mr-1"></i>
            Reports
            <span class="badge badge-secondary ml-1">{{ $reports->total() }}</span>
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-success btn-sm mr-1" id="bulkResolveBtn">
                <i class="fas fa-check-circle mr-1"></i> Resolve Selected
            </button>
            <button type="button" class="btn btn-secondary btn-sm" id="bulkDismissBtn">
                <i class="fas fa-eye-slash mr-1"></i> Dismiss Selected
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-dark">
                <tr>
                    <th style="width:36px"><input type="checkbox" id="selectAllReports" title="Select all pending"></th>
                    <th style="width:60px">#</th>
                    <th>Question</th>
                    <th style="width:110px">Type</th>
                    <th style="width:90px">Status</th>
                    <th style="width:130px">Reported</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                @php $isPending = $report->status === 'pending'; @endphp
                <tr class="{{ $isPending ? 'row-pending' : '' }}">
                    <td class="text-center">
                        <input type="checkbox"
                               class="report-checkbox"
                               value="{{ $report->id }}"
                               data-status="{{ $report->status }}"
                               {{ $isPending ? '' : 'disabled' }}>
                    </td>
                    <td>{{ $report->id }}</td>
                    <td>
                        @if($report->question)
                            <div>{{ \Illuminate\Support\Str::limit($report->question->question, 70) }}</div>
                            <small class="text-muted">
                                {{ $report->question->questionSet->topic->subject->name ?? '' }}
                                &rsaquo; {{ $report->question->questionSet->topic->name ?? '' }}
                                &rsaquo; {{ $report->question->questionSet->name ?? '' }}
                            </small>
                        @else
                            <span class="text-danger"><i class="fas fa-unlink mr-1"></i>Question deleted</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $typeLabels = [
                                'wrong_answer'            => ['Wrong Answer', 'danger'],
                                'typo'                    => ['Typo', 'warning'],
                                'wrong_set'               => ['Wrong Set', 'info'],
                                'confusing_explanation'   => ['Confusing Explanation', 'primary'],
                                'other'                   => ['Other', 'secondary'],
                            ];
                            $type = $typeLabels[$report->report_type] ?? ['Other', 'secondary'];
                        @endphp
                        <span class="badge badge-{{ $type[1] }}">{{ $type[0] }}</span>
                    </td>
                    <td>
                        @if($report->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($report->status === 'reviewed')
                            <span class="badge badge-secondary">Reviewed</span>
                        @else
                            <span class="badge badge-success">Resolved</span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        {{ $report->created_at->format('d M Y, H:i') }}
                        @if($report->reporter_ip)
                            <div class="text-muted small">IP: {{ $report->reporter_ip }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.reports.show', $report) }}"
                           class="btn btn-info btn-xs mr-1" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($report->status !== 'resolved')
                        <button type="button"
                                class="btn btn-success btn-xs mr-1 btn-resolve"
                                data-id="{{ $report->id }}"
                                title="Resolve">
                            <i class="fas fa-check"></i>
                        </button>
                        <form id="resolveForm-{{ $report->id }}"
                              action="{{ route('admin.reports.resolve', $report) }}"
                              method="POST" style="display:none;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="admin_notes" value="">
                        </form>
                        @endif
                        @if($report->status !== 'reviewed')
                        <button type="button"
                                class="btn btn-secondary btn-xs mr-1 btn-dismiss"
                                data-id="{{ $report->id }}"
                                title="Dismiss (reviewed, not an issue)">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        <form id="dismissForm-{{ $report->id }}"
                              action="{{ route('admin.reports.dismiss', $report) }}"
                              method="POST" style="display:none;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="admin_notes" value="">
                        </form>
                        @endif
                        @if($report->status !== 'pending')
                        <button type="button"
                                class="btn btn-warning btn-xs btn-reopen"
                                data-id="{{ $report->id }}"
                                title="Reopen">
                            <i class="fas fa-redo"></i>
                        </button>
                        <form id="reopenForm-{{ $report->id }}"
                              action="{{ route('admin.reports.reopen', $report) }}"
                              method="POST" style="display:none;">
                            @csrf
                            @method('PATCH')
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-flag fa-2x mb-2 d-block"></i>
                        No reports found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reports->hasPages())
    <div class="card-footer">
        {{ $reports->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    $('.select2').select2({ theme: 'bootstrap4' });

    // ===== Filter cascade: Subject → Topic → Set =====
    var allTopics = @json($topics->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'subject_id' => $t->subject_id]));
    var allSets   = @json($questionSets->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'topic_id' => $s->topic_id]));

    function rebuildTopics(subjectId, keepVal) {
        var $sel = $('#filterTopic');
        $sel.empty().append('<option value="">— All Topics —</option>');
        $.each(allTopics, function (_, t) {
            if (!subjectId || t.subject_id == subjectId) {
                $sel.append($('<option>', { value: t.id, text: t.name }));
            }
        });
        if (keepVal) $sel.val(keepVal).trigger('change.select2');
    }

    function rebuildSets(topicId, keepVal) {
        var $sel = $('#filterSet');
        $sel.empty().append('<option value="">— All Sets —</option>');
        $.each(allSets, function (_, s) {
            if (!topicId || s.topic_id == topicId) {
                $sel.append($('<option>', { value: s.id, text: s.name }));
            }
        });
        if (keepVal) $sel.val(keepVal).trigger('change.select2');
    }

    $('#filterSubject').on('change', function () {
        rebuildTopics($(this).val(), null);
        rebuildSets(null, null);
    });
    $('#filterTopic').on('change', function () {
        rebuildSets($(this).val(), null);
    });

    @if($selectedSubjectId)
        rebuildTopics({{ $selectedSubjectId }}, {{ $selectedTopicId ?? 'null' }});
    @endif
    @if($selectedTopicId)
        rebuildSets({{ $selectedTopicId }}, {{ $selectedSetId ?? 'null' }});
    @endif

    // ===== Select All (pending only) =====
    $('#selectAllReports').on('change', function () {
        $('.report-checkbox:not(:disabled)').prop('checked', $(this).is(':checked'));
    });

    // ===== Single Resolve (with optional admin note) =====
    $(document).on('click', '.btn-resolve', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Resolve Report?',
            html: 'Report <b>#' + id + '</b> will be marked as resolved.',
            icon: 'question',
            input: 'textarea',
            inputPlaceholder: 'Optional admin note...',
            inputAttributes: { rows: 3 },
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Resolve',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                var note = (result.value || '').trim();
                $('#resolveForm-' + id + ' input[name="admin_notes"]').val(note);
                document.getElementById('resolveForm-' + id).submit();
            }
        });
    });

    // ===== Single Dismiss (with optional admin note) =====
    $(document).on('click', '.btn-dismiss', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Dismiss Report?',
            html: 'Report <b>#' + id + '</b> will be reviewed and closed (not treated as an issue).',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Optional admin note...',
            inputAttributes: { rows: 3 },
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Dismiss',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                var note = (result.value || '').trim();
                $('#dismissForm-' + id + ' input[name="admin_notes"]').val(note);
                document.getElementById('dismissForm-' + id).submit();
            }
        });
    });

    // ===== Single Reopen =====
    $(document).on('click', '.btn-reopen', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Reopen Report?',
            html: 'Report <b>#' + id + '</b> will be moved back to pending.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Reopen',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('reopenForm-' + id).submit();
            }
        });
    });

    // ===== Bulk Resolve =====
    $('#bulkResolveBtn').on('click', function () {
        var ids = [];
        $('.report-checkbox:checked').each(function () { ids.push($(this).val()); });
        if (ids.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Nothing selected', text: 'Select at least one pending report.', confirmButtonText: 'OK' });
            return;
        }
        Swal.fire({
            title: 'Resolve ' + ids.length + ' Report(s)?',
            text: 'All selected pending reports will be marked as resolved.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, Resolve All',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                var $container = $('#bulkResolveIds');
                $container.empty();
                ids.forEach(function (id) {
                    $container.append('<input type="hidden" name="report_ids[]" value="' + id + '">');
                });
                document.getElementById('bulkResolveForm').submit();
            }
        });
    });

    // ===== Bulk Dismiss =====
    $('#bulkDismissBtn').on('click', function () {
        var ids = [];
        $('.report-checkbox:checked').each(function () { ids.push($(this).val()); });
        if (ids.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Nothing selected', text: 'Select at least one pending report.', confirmButtonText: 'OK' });
            return;
        }
        Swal.fire({
            title: 'Dismiss ' + ids.length + ' Report(s)?',
            text: 'All selected pending reports will be reviewed and closed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            confirmButtonText: 'Dismiss All',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                var $container = $('#bulkDismissIds');
                $container.empty();
                ids.forEach(function (id) {
                    $container.append('<input type="hidden" name="report_ids[]" value="' + id + '">');
                });
                document.getElementById('bulkDismissForm').submit();
            }
        });
    });

});
</script>
@endpush
