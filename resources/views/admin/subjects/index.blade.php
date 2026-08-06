@extends('admin.layouts.app')

@section('title', 'Subjects')
@section('page-title', 'Subjects')

@section('breadcrumb')
    <li class="breadcrumb-item active">Subjects</li>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-2"></i>All Subjects
                    <span class="badge badge-primary ml-2">{{ $subjects->count() }}</span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add New Subject
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table id="subjectsTable"
                       class="table table-bordered table-striped table-hover"
                       style="width:100%">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Topics</th>
                            <th>Color Class</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td class="text-center">{{ $subject->sort_order }}</td>
                            <td class="text-center" style="font-size:1.5rem;">
                                {!! $subject->icon_svg !!}
                            </td>
                            <td>
                                <strong>{{ $subject->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $subject->slug }}</code>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $subject->topics_count }}</span>
                            </td>
                            <td>
                                <small class="text-muted" style="font-size:0.75rem;">
                                    {{ $subject->color_class }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if($subject->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.subjects.edit', $subject) }}"
                                   class="btn btn-sm btn-warning mr-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $subject->id }}"
                                        data-name="{{ $subject->name }}"
                                        data-topics="{{ $subject->topics_count }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>

                                {{-- Hidden delete form --}}
                                <form id="delete-form-{{ $subject->id }}"
                                      action="{{ route('admin.subjects.destroy', $subject) }}"
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

<script>
$(function () {

    // Init DataTable
    $('#subjectsTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [1, 7] }, // icon & actions columns not sortable
        ],
    });

    // Delete with SweetAlert2
    $(document).on('click', '.btn-delete', function () {
        const id      = $(this).data('id');
        const name    = $(this).data('name');
        const topics  = parseInt($(this).data('topics'));

        if (topics > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete',
                html: `<b>${name}</b> has <b>${topics} topic(s)</b>.<br>Delete all topics first before deleting this subject.`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        Swal.fire({
            title: 'Delete Subject?',
            html: `Are you sure you want to delete <b>${name}</b>?<br><small class="text-muted">This action cannot be undone.</small>`,
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
