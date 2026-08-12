@extends('admin.layouts.app')
@section('title', 'Recorded Lectures')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Recorded Lectures</h1>
        <a href="{{ route('admin.recorded-lectures.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Lecture
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Recorded Lectures</h3>
    </div>
    <div class="card-body">
        <table id="lecturesTable" class="table table-bordered table-striped table-hover w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Topic</th>
                    <th>Title</th>
                    <th>Platform</th>
                    <th>Duration</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lectures as $lecture)
                <tr>
                    <td>{{ $lecture->id }}</td>
                    <td>{{ $lecture->topic->subject->name ?? '—' }}</td>
                    <td>{{ $lecture->topic->name ?? '—' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($lecture->title, 45) }}</td>
                    <td><span class="badge badge-danger text-uppercase">{{ $lecture->platform }}</span></td>
                    <td>{{ $lecture->duration_minutes ? $lecture->duration_minutes . ' min' : '—' }}</td>
                    <td>{{ $lecture->sort_order }}</td>
                    <td>
                        @if($lecture->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ $lecture->video_url }}" target="_blank" class="btn btn-xs btn-info mr-1" title="Preview">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ route('admin.recorded-lectures.edit', $lecture) }}" class="btn btn-xs btn-warning mr-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.recorded-lectures.destroy', $lecture) }}" method="POST"
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
    $('#lecturesTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, targets: [8] }]
    });

    // SweetAlert2 delete confirm
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Delete this lecture?',
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
