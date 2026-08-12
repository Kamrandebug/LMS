@extends('admin.layouts.app')
@section('title', 'Resource Materials')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Resource Materials</h1>
        <a href="{{ route('admin.resource-materials.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Material
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Resource Materials</h3>
    </div>
    <div class="card-body">
        <table id="materialsTable" class="table table-bordered table-striped table-hover w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Topic</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $material)
                <tr>
                    <td>{{ $material->id }}</td>
                    <td>{{ $material->topic->subject->name ?? '—' }}</td>
                    <td>{{ $material->topic->name ?? '—' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($material->title, 50) }}</td>
                    <td><span class="badge badge-secondary text-uppercase">{{ $material->file_type }}</span></td>
                    <td>{{ $material->sort_order }}</td>
                    <td>
                        @if($material->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ $material->getStorageUrl() ?? '#' }}" target="_blank" class="btn btn-xs btn-info mr-1" title="Preview">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ route('admin.resource-materials.edit', $material) }}" class="btn btn-xs btn-warning mr-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.resource-materials.destroy', $material) }}" method="POST"
                              class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('#materialsTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, targets: [7] }]
    });

    // SweetAlert2 delete confirm (same pattern as existing admin views)
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Delete this material?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
