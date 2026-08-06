@extends('admin.layouts.app')

@section('title', 'Report #' . $report->id)
@section('page-title', 'Report #' . $report->id)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Question Reports</a></li>
    <li class="breadcrumb-item active">Report #{{ $report->id }}</li>
@endsection

@section('content')

{{-- ===== Status Banner ===== --}}
@if($report->status === 'pending')
    <div class="alert alert-warning">
        <i class="fas fa-clock mr-1"></i>
        <strong>Pending</strong> — this report has not been reviewed yet.
    </div>
@elseif($report->status === 'reviewed')
    <div class="alert alert-secondary">
        <i class="fas fa-check-double mr-1"></i>
        <strong>Reviewed</strong> — this report was reviewed and closed (not treated as an issue).
    </div>
@else
    <div class="alert alert-success">
        <i class="fas fa-check-circle mr-1"></i>
        <strong>Resolved</strong> — this report was marked as resolved.
    </div>
@endif

<div class="row">

    {{-- ===== LEFT: Report Details ===== --}}
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-alt mr-1"></i> Report Details
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Reports
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:160px;">Report ID</td>
                        <td><strong>#{{ $report->id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Report Type</td>
                        <td>
                            @php
                                $typeLabels = [
                                    'wrong_answer'          => ['Wrong Answer', 'danger'],
                                    'typo'                  => ['Typo', 'warning'],
                                    'wrong_set'             => ['Wrong Set', 'info'],
                                    'confusing_explanation' => ['Confusing Explanation', 'primary'],
                                    'other'                 => ['Other', 'secondary'],
                                ];
                                $type = $typeLabels[$report->report_type] ?? ['Other', 'secondary'];
                            @endphp
                            <span class="badge badge-{{ $type[1] }}">{{ $type[0] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($report->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($report->status === 'reviewed')
                                <span class="badge badge-secondary">Reviewed</span>
                            @else
                                <span class="badge badge-success">Resolved</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Reported</td>
                        <td>{{ $report->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($report->reporter_ip)
                    <tr>
                        <td class="text-muted">Reporter IP</td>
                        <td><code>{{ $report->reporter_ip }}</code></td>
                    </tr>
                    @endif
                </table>

                <hr>

                <h6 class="text-muted"><i class="fas fa-comment mr-1"></i> Student's Description</h6>
                <p class="mb-0">
                    {!! nl2br(e($report->description ?? 'No description provided.')) !!}
                </p>
            </div>
        </div>

        @if($report->admin_notes)
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sticky-note mr-1"></i> Admin Notes</h3>
            </div>
            <div class="card-body">
                {!! nl2br(e($report->admin_notes)) !!}
            </div>
        </div>
        @endif
    </div>

    {{-- ===== RIGHT: Question Preview + Actions ===== --}}
    <div class="col-lg-4 col-12">

        {{-- Actions --}}
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> Actions</h3>
            </div>
            <div class="card-body">
                @if($report->status !== 'resolved')
                <button type="button" class="btn btn-success btn-block mb-2 btn-resolve" data-id="{{ $report->id }}">
                    <i class="fas fa-check mr-1"></i> Resolve
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
                <button type="button" class="btn btn-secondary btn-block mb-2 btn-dismiss" data-id="{{ $report->id }}">
                    <i class="fas fa-eye-slash mr-1"></i> Dismiss (Not an Issue)
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
                <button type="button" class="btn btn-warning btn-block btn-reopen" data-id="{{ $report->id }}">
                    <i class="fas fa-redo mr-1"></i> Reopen
                </button>
                <form id="reopenForm-{{ $report->id }}"
                      action="{{ route('admin.reports.reopen', $report) }}"
                      method="POST" style="display:none;">
                    @csrf
                    @method('PATCH')
                </form>
                @endif
            </div>
        </div>

        {{-- Question Preview --}}
        @if($report->question)
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-question-circle mr-1"></i> Question Preview</h3>
            </div>
            <div class="card-body">
                <p class="font-weight-bold mb-3">{{ $report->question->question }}</p>

                @php
                    $optionFields = ['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'];
                @endphp

                @foreach($optionFields as $key => $field)
                    @php
                        $isCorrect = $report->question->correct_option === $key;
                    @endphp
                    <div class="d-flex align-items-start mb-2 p-2 rounded {{ $isCorrect ? 'bg-success text-white' : 'bg-light' }}">
                        <span class="font-weight-bold mr-2" style="min-width:24px;">
                            {{ strtoupper($key) }})
                        </span>
                        <span>{{ $report->question->$field }}</span>
                        @if($isCorrect)
                            <i class="fas fa-check ml-auto"></i>
                        @endif
                    </div>
                @endforeach

                <hr>
                <small class="text-muted">
                    <strong>Location:</strong><br>
                    {!! $report->question->questionSet->topic->subject->icon_svg ?? '' !!}
                    {{ $report->question->questionSet->topic->subject->name ?? '' }}
                    &rsaquo; {{ $report->question->questionSet->topic->name ?? '' }}
                    &rsaquo; {{ $report->question->questionSet->name ?? '' }}
                </small>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('admin.questions.edit', $report->question) }}"
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-edit mr-1"></i> Edit This Question
                </a>
            </div>
        </div>
        @else
        <div class="card card-outline card-danger">
            <div class="card-body text-center py-4">
                <i class="fas fa-unlink fa-2x mb-2 d-block text-danger"></i>
                <p class="text-danger">The reported question has been deleted.</p>
            </div>
        </div>
        @endif

    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    // Resolve with optional admin note
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

    // Dismiss with optional admin note
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

    // Reopen
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

});
</script>
@endpush
