@extends('admin.layouts.app')

@section('title', 'Question Sets')
@section('page-title', 'Question Sets')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Question Sets</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
        <form method="GET" action="{{ route('admin.question-sets.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.question-sets.index') }}" class="btn btn-secondary">
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
            <i class="fas fa-layer-group mr-1"></i>
            Question Sets
            @if($selectedTopicId || $selectedSubjectId)
                <span class="badge badge-info ml-2">Filtered</span>
            @endif
        </h3>
        <div class="card-tools">
            <a href="{{ route('admin.question-sets.create', array_filter([
                'subject_id' => $selectedSubjectId,
                'topic_id'   => $selectedTopicId,
            ])) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Question Set
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table id="questionSetsTable" class="table table-bordered table-striped table-hover mb-0">
            <thead class="thead-dark">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Subject</th>
                    <th>Topic</th>
                    <th>Name</th>
                    <th style="width:70px">Set #</th>
                    <th>Slug</th>
                    <th style="width:80px">Order</th>
                    <th style="width:90px">Questions</th>
                    <th style="width:80px">Active</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questionSets as $set)
                <tr>
                    <td>{{ $set->id }}</td>
                    <td>
                        <small>
                            {!! $set->topic->subject->icon_svg ?? '' !!}
                            {{ $set->topic->subject->name ?? '—' }}
                        </small>
                    </td>
                    <td>{{ $set->topic->name ?? '—' }}</td>
                    <td><strong>{{ $set->name }}</strong></td>
                    <td class="text-center">{{ $set->set_number }}</td>
                    <td><code class="small">{{ $set->slug }}</code></td>
                    <td class="text-center">{{ $set->sort_order ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $set->questions_count > 0 ? 'success' : 'secondary' }}">
                            {{ $set->questions_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($set->is_active)
                            <span class="badge badge-success">Yes</span>
                        @else
                            <span class="badge badge-secondary">No</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.question-sets.edit', $set) }}"
                           class="btn btn-warning btn-xs mr-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button"
                                class="btn btn-danger btn-xs btn-delete"
                                data-id="{{ $set->id }}"
                                data-name="{{ addslashes($set->name) }}"
                                data-questions="{{ $set->questions_count }}"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="deleteForm-{{ $set->id }}"
                              action="{{ route('admin.question-sets.destroy', $set) }}"
                              method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-layer-group fa-2x mb-2 d-block"></i>
                        No question sets found.
                        <a href="{{ route('admin.question-sets.create') }}">Add the first one.</a>
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

    // DataTables
    $('#questionSetsTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[1, 'asc'], [2, 'asc'], [6, 'asc']],
        columnDefs: [
            { orderable: false, targets: [9] }
        ]
    });

    // Select2
    $('.select2').select2({ theme: 'bootstrap4' });

    // Subject → cascade Topic filter
    var allTopics = @json($topics->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'subject_id' => $t->subject_id]));

    $('#filterSubject').on('change', function () {
        var subjectId   = $(this).val();
        var $topicSel   = $('#filterTopic');
        var currentVal  = $topicSel.val();

        $topicSel.empty().append('<option value="">— All Topics —</option>');

        $.each(allTopics, function (_, topic) {
            if (!subjectId || topic.subject_id == subjectId) {
                $topicSel.append(
                    $('<option>', { value: topic.id, text: topic.name })
                );
            }
        });

        // Keep selected if still in list
        if (currentVal) {
            $topicSel.val(currentVal).trigger('change.select2');
        }
    });

    // Trigger on load to filter topic dropdown to match pre-selected subject
    @if($selectedSubjectId)
    $('#filterSubject').trigger('change');
    @if($selectedTopicId)
    $('#filterTopic').val({{ $selectedTopicId }}).trigger('change.select2');
    @endif
    @endif

    // Delete with SweetAlert2
    $(document).on('click', '.btn-delete', function () {
        var id        = $(this).data('id');
        var name      = $(this).data('name');
        var questions = parseInt($(this).data('questions'));

        if (questions > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete',
                html: '<b>' + name + '</b> has <b>' + questions + ' question(s)</b>.<br>Delete all questions first.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6c757d',
            });
            return;
        }

        Swal.fire({
            title: 'Delete Question Set?',
            html: 'Are you sure you want to delete <b>' + name + '</b>?<br><small class="text-muted">This cannot be undone.</small>',
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
