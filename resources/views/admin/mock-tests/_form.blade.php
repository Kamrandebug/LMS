{{--
    Shared form partial for MockExam create and edit.
    Variables expected:
      $mockExam  — MockExam model (null on create, populated on edit)
--}}

@php
    $isEdit = isset($mockExam) && $mockExam->exists;
@endphp

{{-- ======= NAME ======= --}}
<div class="form-group">
    <label for="name">
        Name <span class="text-danger">*</span>
    </label>
    <input type="text"
           id="name"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $isEdit ? $mockExam->name : '') }}"
           placeholder="e.g. MDCAT 2024 Full Mock Test"
           required>
    @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

{{-- ======= SLUG ======= --}}
<div class="form-group">
    <label for="slug">
        Slug <span class="text-danger">*</span>
    </label>
    <div class="input-group">
        <input type="text"
               id="slug"
               name="slug"
               class="form-control @error('slug') is-invalid @enderror"
               value="{{ old('slug', $isEdit ? $mockExam->slug : '') }}"
               placeholder="auto-generated from name"
               required>
        @if($isEdit)
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" id="regenSlugBtn" title="Re-generate from name">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        @endif
    </div>
    <small class="text-muted">Lowercase letters, numbers, hyphens only. Must be globally unique.</small>
    @error('slug')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

{{-- ======= EXAM SETTINGS ROW ======= --}}
<div class="row">

    {{-- Duration --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="duration_minutes">
                Duration (minutes) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="number"
                       id="duration_minutes"
                       name="duration_minutes"
                       class="form-control @error('duration_minutes') is-invalid @enderror"
                       value="{{ old('duration_minutes', $isEdit ? $mockExam->duration_minutes : '') }}"
                       min="1"
                       max="600"
                       placeholder="e.g. 180"
                       required>
                <div class="input-group-append">
                    <span class="input-group-text">min</span>
                </div>
            </div>
            <small class="text-muted">
                <span id="durationPreview"></span>
            </small>
            @error('duration_minutes')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Total Questions --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="total_questions">
                Total Questions (target)
            </label>
            <input type="number"
                   id="total_questions"
                   name="total_questions"
                   class="form-control @error('total_questions') is-invalid @enderror"
                   value="{{ old('total_questions', $isEdit ? $mockExam->total_questions : '') }}"
                   min="1"
                   placeholder="e.g. 100">
            <small class="text-muted">Target number. Shown alongside actual count.</small>
            @error('total_questions')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

</div>

{{-- ======= ACTIVE TOGGLE ======= --}}
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox"
               class="custom-control-input"
               id="is_active"
               name="is_active"
               value="1"
               {{ old('is_active', $isEdit ? $mockExam->is_active : false) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">
            Active (visible to students)
        </label>
    </div>
    <small class="text-muted">Keep inactive until you've finished adding questions.</small>
</div>
