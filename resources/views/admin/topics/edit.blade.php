@extends('admin.layouts.app')

@section('title', 'Edit Topic')
@section('page-title', 'Edit Topic')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.topics.index') }}">Topics</a>
    </li>
    <li class="breadcrumb-item active">Edit: {{ $topic->name }}</li>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-warning">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Editing: <strong>{{ $topic->name }}</strong>
                </h3>
            </div>

            <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Subject --}}
                    <div class="form-group">
                        <label for="subject_id">
                            Subject <span class="text-danger">*</span>
                            <small class="text-muted ml-1">⚠ Changing subject will move this topic</small>
                        </label>
                        <select id="subject_id"
                                name="subject_id"
                                class="form-control select2 @error('subject_id') is-invalid @enderror"
                                required>
                            <option value="">— Select Subject —</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $topic->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {!! $subject->icon_svg !!} {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name">
                            Topic Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $topic->name) }}"
                               required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="form-group">
                        <label for="slug">
                            Slug <span class="text-danger">*</span>
                            <small class="text-muted ml-1">⚠ Changing slug will break existing URLs</small>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">/</span>
                            </div>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $topic->slug) }}"
                                   required>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">Description <small class="text-muted">(optional)</small></label>
                        <textarea id="description"
                                  name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3">{{ old('description', $topic->description ?? '') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number"
                               id="sort_order"
                               name="sort_order"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', $topic->sort_order ?? 0) }}"
                               min="0"
                               style="max-width:150px;">
                        @error('sort_order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Active Status --}}
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $topic->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                Active (visible to students)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Topic
                        </button>
                        <a href="{{ route('admin.topics.index', ['subject_id' => $topic->subject_id]) }}"
                           class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    <small class="text-muted">
                        ID: {{ $topic->id }} &nbsp;|&nbsp;
                        Created: {{ $topic->created_at->format('d M Y') }}
                    </small>
                </div>

            </form>
        </div>
    </div>

    {{-- Stats + Danger Zone --}}
    <div class="col-lg-4 col-12">

        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Topic Stats</h3>
            </div>
            <div class="card-body">
                @php
                    $setsCount = $topic->questionSets()->count();
                @endphp
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Subject</td>
                        <td>
                            <span class="badge badge-info">
                                {!! $topic->subject->icon_svg ?? '' !!}
                                {{ $topic->subject->name ?? '—' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Question Sets</td>
                        <td><span class="badge badge-success">{{ $setsCount }}</span></td>
                    </tr>
                    <tr>
                        <td>Slug</td>
                        <td><code>{{ $topic->slug }}</code></td>
                    </tr>
                    <tr>
                        <td>Sort Order</td>
                        <td>{{ $topic->sort_order ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($topic->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title text-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Danger Zone
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Deletion is blocked if this topic has question sets.
                    Remove all question sets first.
                </p>
                <form action="{{ route('admin.topics.destroy', $topic) }}"
                      method="POST" id="deleteTopicForm">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            id="deleteTopicBtn"
                            data-sets="{{ $setsCount }}"
                            data-name="{{ addslashes($topic->name) }}">
                        <i class="fas fa-trash mr-1"></i> Delete This Topic
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    // Select2
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    // Delete from edit page
    $('#deleteTopicBtn').on('click', function () {
        const sets = parseInt($(this).data('sets'));
        const name = $(this).data('name');

        if (sets > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete',
                html: `<b>${name}</b> has <b>${sets} question set(s)</b>.<br>Delete all sets first.`,
                confirmButtonText: 'OK',
            });
            return;
        }

        Swal.fire({
            title: 'Delete Topic?',
            html: `Delete <b>${name}</b> permanently?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteTopicForm').submit();
            }
        });
    });

});
</script>
@endpush
