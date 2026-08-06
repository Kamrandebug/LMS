@extends('admin.layouts.app')

@section('title', 'Add Topic')
@section('page-title', 'Add New Topic')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.topics.index') }}">Topics</a>
    </li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-2"></i>Topic Details
                </h3>
            </div>

            <form action="{{ route('admin.topics.store') }}" method="POST">
                @csrf

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

                    {{-- Subject (Required) --}}
                    <div class="form-group">
                        <label for="subject_id">
                            Subject <span class="text-danger">*</span>
                        </label>
                        <select id="subject_id"
                                name="subject_id"
                                class="form-control select2 @error('subject_id') is-invalid @enderror"
                                required>
                            <option value="">— Select Subject —</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ (old('subject_id', $selectedSubject) == $subject->id) ? 'selected' : '' }}>
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
                               value="{{ old('name') }}"
                               placeholder="e.g. Synonyms, Pakistan History, Islamiat"
                               autofocus
                               required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="form-group">
                        <label for="slug">
                            Slug <span class="text-danger">*</span>
                            <small class="text-muted ml-1">(unique within the selected subject)</small>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">/</span>
                            </div>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug') }}"
                                   placeholder="e.g. synonyms"
                                   required>
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">
                            Description
                            <small class="text-muted ml-1">(optional)</small>
                        </label>
                        <textarea id="description"
                                  name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Brief description of this topic (shown to students)">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div class="form-group">
                        <label for="sort_order">
                            Sort Order
                            <small class="text-muted ml-1">(optional — lower number appears first)</small>
                        </label>
                        <input type="number"
                               id="sort_order"
                               name="sort_order"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               value="{{ old('sort_order', 0) }}"
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
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                Active (visible to students)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Topic
                    </button>
                    <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Tips --}}
    <div class="col-lg-4 col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Tips</h3>
            </div>
            <div class="card-body">
                <p><strong>Subject:</strong> Select which subject this topic belongs to.</p>
                <p><strong>Slug:</strong> Auto-generated. Must be unique per subject — two different subjects can have a topic with the same slug.</p>
                <p><strong>Sort Order:</strong> Controls display order within a subject. Use 1, 2, 3… or leave at 0.</p>
                <p><strong>Description:</strong> Optional text shown below the topic name on the subject page.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function () {

    // Init Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: '— Select Subject —',
    });

    // Auto-generate slug from name
    $('#name').on('input', function () {
        let slug = $(this).val()
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });

});
</script>
@endpush
