@extends('admin.layouts.app')

@section('title', 'Edit Subject')
@section('page-title', 'Edit Subject')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.subjects.index') }}">Subjects</a>
    </li>
    <li class="breadcrumb-item active">Edit: {{ $subject->name }}</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-warning">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Editing: <strong>{{ $subject->name }}</strong>
                </h3>
            </div>

            <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
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

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name">
                            Subject Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $subject->name) }}"
                               required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="form-group">
                        <label for="slug">
                            Slug <span class="text-danger">*</span>
                            <small class="text-muted ml-1">
                                ⚠ Changing the slug will break existing URLs for this subject.
                            </small>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">/</span>
                            </div>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $subject->slug) }}"
                                   required>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description"
                                  name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Brief description of the subject...">{{ old('description', $subject->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        {{-- Icon --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="icon_svg">
                                    Icon (Emoji) <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="icon_svg"
                                       name="icon_svg"
                                       class="form-control @error('icon_svg') is-invalid @enderror"
                                       value="{{ old('icon_svg', $subject->icon_svg) }}"
                                       maxlength="10"
                                       required>
                                <small class="form-text text-muted">
                                    Preview: <span id="iconPreview" style="font-size:1.5rem;">{{ $subject->icon_svg }}</span>
                                </small>
                                @error('icon_svg')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Sort Order --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order">
                                    Sort Order <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       id="sort_order"
                                       name="sort_order"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       value="{{ old('sort_order', $subject->sort_order) }}"
                                       min="0"
                                       required>
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Color Class --}}
                    <div class="form-group">
                        <label for="color_class">
                            Card Gradient Color <span class="text-danger">*</span>
                        </label>
                        <select id="color_class"
                                name="color_class"
                                class="form-control @error('color_class') is-invalid @enderror"
                                required>
                            <option value="">— Select a gradient —</option>
                            @foreach($colorOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('color_class', $subject->color_class) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                            @if(!array_key_exists($subject->color_class, $colorOptions))
                                <option value="{{ $subject->color_class }}" selected>
                                    Custom: {{ $subject->color_class }}
                                </option>
                            @endif
                        </select>
                        @error('color_class')
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
                                   {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                Active (visible on site)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Subject
                        </button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    <div>
                        <small class="text-muted">
                            ID: {{ $subject->id }} &nbsp;|&nbsp;
                            Created: {{ $subject->created_at->format('d M Y') }}
                        </small>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="col-lg-4 col-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Subject Stats</h3>
            </div>
            <div class="card-body">
                @php
                    $topicsCount = $subject->topics()->count();
                @endphp
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Topics</td>
                        <td><span class="badge badge-info">{{ $topicsCount }}</span></td>
                    </tr>
                    <tr>
                        <td>Slug</td>
                        <td><code>{{ $subject->slug }}</code></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($subject->is_active)
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
                    Deleting this subject will fail if it has topics.
                    Remove all topics first.
                </p>
                <form action="{{ route('admin.subjects.destroy', $subject) }}"
                      method="POST" id="deleteSubjectForm">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            id="deleteSubjectBtn">
                        <i class="fas fa-trash mr-1"></i> Delete This Subject
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

    // Live icon preview
    $('#icon_svg').on('input', function () {
        $('#iconPreview').text($(this).val());
    });

    // Delete from edit page
    $('#deleteSubjectBtn').on('click', function () {
        const topics = {{ $subject->topics()->count() }};
        const name   = "{{ addslashes($subject->name) }}";

        if (topics > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete',
                html: `<b>${name}</b> has <b>${topics} topic(s)</b>.<br>Delete all topics first.`,
                confirmButtonText: 'OK',
            });
            return;
        }

        Swal.fire({
            title: 'Delete Subject?',
            html: `Are you sure you want to delete <b>${name}</b>?<br>
                   <small class="text-muted">This action cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteSubjectForm').submit();
            }
        });
    });

});
</script>
@endpush
