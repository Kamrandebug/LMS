{{--
    Shared form fields for QuestionSet create and edit.
    Variables expected in scope:
      $subjects        — Collection of all subjects
      $topics          — Collection of all topics (all, not filtered)
      $questionSet     — QuestionSet model (null on create, populated on edit)
      $selectedSubject — int|null (pre-select a subject on create)
      $selectedTopic   — int|null (pre-select a topic on create)
--}}

@php
    $isEdit  = isset($questionSet) && $questionSet->exists;
    $oldTopicId   = old('topic_id',   $isEdit ? $questionSet->topic_id   : $selectedTopic);
    $oldSubjectId = old('subject_id', $isEdit ? ($questionSet->topic->subject_id ?? null) : $selectedSubject);
@endphp

{{-- Subject (UI only — not saved to DB) --}}
<div class="form-group">
    <label for="subject_id_ui">
        Subject <span class="text-danger">*</span>
    </label>
    <select id="subject_id_ui" class="form-control select2 select2-bootstrap4">
        <option value="">— Select Subject —</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}"
                {{ $oldSubjectId == $subject->id ? 'selected' : '' }}>
                {!! $subject->icon_svg ?? '' !!} {{ $subject->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Select a subject first to filter topics below.</small>
</div>

{{-- Topic (saved to DB) --}}
<div class="form-group">
    <label for="topic_id">
        Topic <span class="text-danger">*</span>
    </label>
    <select id="topic_id"
            name="topic_id"
            class="form-control select2 select2-bootstrap4 @error('topic_id') is-invalid @enderror"
            required>
        <option value="">— Select Topic —</option>
        @foreach($topics as $topic)
            <option value="{{ $topic->id }}"
                    data-subject="{{ $topic->subject_id }}"
                    {{ $oldTopicId == $topic->id ? 'selected' : '' }}>
                {{ $topic->name }}
            </option>
        @endforeach
    </select>
    @error('topic_id')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

{{-- Name --}}
<div class="form-group">
    <label for="name">
        Name <span class="text-danger">*</span>
    </label>
    <input type="text"
           id="name"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $isEdit ? $questionSet->name : '') }}"
           placeholder="e.g. Set 1, Past Papers 2020"
           required>
    @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

{{-- Slug --}}
<div class="form-group">
    <label for="slug">
        Slug <span class="text-danger">*</span>
    </label>
    <div class="input-group">
        <input type="text"
               id="slug"
               name="slug"
               class="form-control @error('slug') is-invalid @enderror"
               value="{{ old('slug', $isEdit ? $questionSet->slug : '') }}"
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
    <small class="text-muted">Lowercase letters, numbers, hyphens only. Unique within the same topic.</small>
    @error('slug')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

{{-- Set Number --}}
<div class="form-group">
    <label for="set_number">
        Set Number <span class="text-danger">*</span>
    </label>
    <input type="number"
           id="set_number"
           name="set_number"
           class="form-control @error('set_number') is-invalid @enderror"
           value="{{ old('set_number', $isEdit ? $questionSet->set_number : '') }}"
           min="1"
           placeholder="e.g. 1"
           required>
    <small class="text-muted">Used for identifying the set (e.g., Set 1). Must be unique within the topic.</small>
    @error('set_number')
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
           value="{{ old('sort_order', $isEdit ? $questionSet->sort_order : '') }}"
           min="0"
           placeholder="0">
    <small class="text-muted">Lower number = shown first. Leave blank for default ordering.</small>
    @error('sort_order')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

{{-- Active Toggle --}}
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox"
               class="custom-control-input"
               id="is_active"
               name="is_active"
               value="1"
               {{ old('is_active', $isEdit ? $questionSet->is_active : true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">
            Active (visible to students)
        </label>
    </div>
</div>
