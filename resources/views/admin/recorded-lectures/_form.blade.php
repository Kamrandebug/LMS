{{-- Shared form partial for create and edit --}}

{{-- Topic (grouped by subject, Select2) --}}
<div class="form-group">
    <label for="topic_id">Topic <span class="text-danger">*</span></label>
    <select name="topic_id" id="topic_id"
            class="form-control select2 @error('topic_id') is-invalid @enderror" required>
        <option value="">— Select Topic —</option>
        @foreach($subjects as $subject)
            <optgroup label="{{ $subject->name }}">
                @foreach($subject->topics as $topic)
                    <option value="{{ $topic->id }}"
                        {{ old('topic_id', $recordedLecture->topic_id ?? '') == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    @error('topic_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Title --}}
<div class="form-group">
    <label for="title">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title"
           class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $recordedLecture->title ?? '') }}" maxlength="255" required>
    @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Description --}}
<div class="form-group">
    <label for="description">Description <small class="text-muted">(optional)</small></label>
    <textarea name="description" id="description"
              class="form-control @error('description') is-invalid @enderror"
              rows="3" maxlength="2000">{{ old('description', $recordedLecture->description ?? '') }}</textarea>
    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Video URL --}}
<div class="form-group">
    <label for="video_url">YouTube / Video URL <span class="text-danger">*</span></label>
    <input type="url" name="video_url" id="video_url"
           class="form-control @error('video_url') is-invalid @enderror"
           value="{{ old('video_url', $recordedLecture->video_url ?? '') }}"
           placeholder="https://www.youtube.com/watch?v=..." maxlength="1000" required>
    <small class="form-text text-muted">Paste the full YouTube watch URL. The embed is auto-generated.</small>
    @error('video_url') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Platform --}}
<div class="form-group">
    <label for="platform">Platform <span class="text-danger">*</span></label>
    <select name="platform" id="platform"
            class="form-control @error('platform') is-invalid @enderror" required>
        @foreach(['youtube' => 'YouTube', 'vimeo' => 'Vimeo', 'other' => 'Other'] as $val => $label)
            <option value="{{ $val }}"
                {{ old('platform', $recordedLecture->platform ?? 'youtube') === $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('platform') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Duration --}}
<div class="form-group">
    <label for="duration_minutes">Duration (minutes) <small class="text-muted">(optional)</small></label>
    <input type="number" name="duration_minutes" id="duration_minutes"
           class="form-control @error('duration_minutes') is-invalid @enderror"
           value="{{ old('duration_minutes', $recordedLecture->duration_minutes ?? '') }}"
           min="1" max="999" placeholder="e.g. 45">
    @error('duration_minutes') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Sort Order --}}
<div class="form-group">
    <label for="sort_order">Sort Order</label>
    <input type="number" name="sort_order" id="sort_order"
           class="form-control @error('sort_order') is-invalid @enderror"
           value="{{ old('sort_order', $recordedLecture->sort_order ?? 0) }}" min="0">
    <small class="form-text text-muted">Lower numbers appear first. Default: 0.</small>
    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Is Active --}}
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active"
               class="custom-control-input" value="1"
               {{ old('is_active', $recordedLecture->is_active ?? true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active (visible to students)</label>
    </div>
</div>
