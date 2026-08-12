@extends('admin.layouts.app')
@section('title', 'Add Resource Material')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Add Resource Material</h1>
        <a href="{{ route('admin.resource-materials.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.resource-materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @php $resourceMaterial = null; @endphp
            @include('admin.resource-materials._form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Save Material
                </button>
                <a href="{{ route('admin.resource-materials.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function () {
    $('.select2').select2({ theme: 'bootstrap4' });

    // Update custom-file label on file selection (Bootstrap 4)
    $('.custom-file-input').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').text(fileName || $(this).next('.custom-file-label').data('default'));
    });
});
</script>
@endpush
