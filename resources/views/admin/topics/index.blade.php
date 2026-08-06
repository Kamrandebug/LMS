@extends('admin.layouts.app')

@section('title', 'Topics')
@section('page-title', 'Topics')

@section('breadcrumb')
    <li class="breadcrumb-item active">Topics</li>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')

{{-- Subject Filter Bar --}}
<div class="row mb-3">
    <div class="col-lg-5 col-12">
        <form method="GET" action="{{ route('admin.topics.index') }}" id="filterForm">
            <div class="input-group">
                <select name="subject_id" id="subjectFilter" class="form-control select2">
                    <option value="">— All Subjects —</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                            {!! $subject->icon_svg !!} {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($selectedSubjectId)
                        <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-7 col-12 text-right">
        <a href="{{ route('admin.topics.create', $selectedSubjectId ? ['subject_id' => $selectedSubjectId] : []) }}"
           class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Add New Topic
        </a>
    </div>
</div>

{{-- Topics Table --}}
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    @if($selectedSubjectId && $subjects->where('id', $selectedSubjectId)->first())
                        Topics in: <strong>{{ $subjects->where('id', $selectedSubjectId)->first()->name }}</strong>
                    @else
                        All Topics
                    @endif
                    <span class="badge badge-primary ml-2">{{ $topics->count() }}</span>
                </h3>
            </div>

            <div class="card-body">
                <table id="topicsTable"
                       class="table table-bordered table-striped table-hover"
                       style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Topic Name</th>
                            <th>Slug</th>
                            <th>Sets</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topics as $index => $topic)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {!! $topic->subject->icon_svg ?? '' !!}
                                    {{ $topic->subject->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $topic->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $topic->slug }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">
                                    {{ $topic->question_sets_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $topic->sort_order ?? '—' }}
                            </td>
                            <td class="text-center">
                                @if($topic->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.topics.edit', $topic) }}"
                                   class="btn btn-sm btn-warning mr-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $topic->id }}"
                                        data-name="{{ $topic->name }}"
                                        data-sets="{{ $topic->question_sets_count }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>

                                <form id="delete-form-{{ $topic->id }}"
                                      action="{{ route('admin.topics.destroy', $topic) }}"
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

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(function () {

    // Init Select2 for subject filter
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: '— All Subjects —',
        allowClear: true,
    });

    // Auto-submit filter when Select2 changes
    $('#subjectFilter').on('change', function () {
        $('#filterForm').submit();
    });

    // Init DataTable
    $('#topicsTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[1, 'asc'], [5, 'asc'], [2, 'asc']],
        columnDefs: [
            { orderable: false, targets: [7] },
        ],
    });

    // Delete with SweetAlert2
    $(document).on('click', '.btn-delete', function () {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        const sets = parseInt($(this).data('sets'));

        if (sets > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete',
                html: `<b>${name}</b> has <b>${sets} question set(s)</b>.<br>Delete all question sets first.`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        Swal.fire({
            title: 'Delete Topic?',
            html: `Delete topic <b>${name}</b>?<br>
                   <small class="text-muted">This cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });

});
</script>
@endpush
