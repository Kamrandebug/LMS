@extends('admin.layouts.app')

@section('title', 'Add Subject')
@section('page-title', 'Add New Subject')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.subjects.index') }}">Subjects</a>
    </li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-primary">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus mr-2"></i>Subject Details
                </h3>
            </div>

            <form action="{{ route('admin.subjects.store') }}" method="POST">
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

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name">
                            Subject Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. English, General Knowledge"
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
                            <small class="text-muted ml-1">(auto-generated, lowercase, hyphens only)</small>
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
                                   placeholder="e.g. general-knowledge"
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
                                  placeholder="Brief description of the subject...">{{ old('description') }}</textarea>
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
                                       value="{{ old('icon_svg') }}"
                                       placeholder="e.g. 📚  🧠  ⚖️"
                                       maxlength="10"
                                       required>
                                <small class="form-text text-muted">
                                    Paste one emoji. Preview: <span id="iconPreview" style="font-size:1.5rem;"></span>
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
                                       value="{{ old('sort_order', $nextOrder) }}"
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
                                    {{ old('color_class') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
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
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                Active (visible on site)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Subject
                    </button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Tips Card --}}
    <div class="col-lg-4 col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Tips</h3>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> Use the full subject name as it will appear on the site.</p>
                <p><strong>Slug:</strong> Auto-generated from the name. Used in the URL.</p>
                <p><strong>Icon:</strong> Paste one emoji — it appears on the subject card.</p>
                <p><strong>Sort Order:</strong> Lower numbers appear first in lists.</p>
                <p><strong>Gradient:</strong> Controls the background color of the subject card.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {

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

    // Live icon preview
    $('#icon_svg').on('input', function () {
        $('#iconPreview').text($(this).val());
    });
    // trigger on load if old value present
    $('#iconPreview').text($('#icon_svg').val());

});
</script>
@endpush
