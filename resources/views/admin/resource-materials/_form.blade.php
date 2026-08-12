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
                        {{ old('topic_id', $resourceMaterial->topic_id ?? '') == $topic->id ? 'selected' : '' }}>
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
           value="{{ old('title', $resourceMaterial->title ?? '') }}" maxlength="255" required>
    @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Description --}}
<div class="form-group">
    <label for="description">Description <small class="text-muted">(optional)</small></label>
    <textarea name="description" id="description"
              class="form-control @error('description') is-invalid @enderror"
              rows="3" maxlength="2000">{{ old('description', $resourceMaterial->description ?? '') }}</textarea>
    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- File Upload --}}
<div class="form-group">
    <label for="file">Upload File <span class="text-danger">*</span></label>
    <div class="custom-file">
        <input type="file" name="file" id="file"
               class="custom-file-input @error('file') is-invalid @enderror"
               accept=".pdf,.csv,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
        <label class="custom-file-label" for="file" data-default="Choose file">Choose file</label>
    </div>
    <small class="form-text text-muted">
        Accepted: PDF, CSV, Word, PowerPoint, Excel (max 50 MB).
        @if(!empty($resourceMaterial->file_path))
            <br>Current file: <strong>{{ basename($resourceMaterial->file_path) }}</strong>
            <a href="{{ $resourceMaterial->getStorageUrl() }}" target="_blank" class="ml-1">
                <i class="fas fa-external-link-alt fa-xs"></i> Preview
            </a>
        @endif
    </small>
    @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- OR paste a URL (optional fallback) --}}
<div class="form-group">
    <label for="file_url">Or paste a File URL <small class="text-muted">(optional)</small></label>
    <input type="url" name="file_url" id="file_url"
           class="form-control @error('file_url') is-invalid @enderror"
           value="{{ old('file_url', $resourceMaterial->file_url ?? '') }}"
           placeholder="https://drive.google.com/..." maxlength="1000">
    <small class="form-text text-muted">If you upload a file above, this URL will be ignored.</small>
    @error('file_url') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- File Type (auto-detected, hidden) --}}
<input type="hidden" name="file_type" value="{{ old('file_type', $resourceMaterial->file_type ?? '') }}">

{{-- Sort Order --}}
<div class="form-group">
    <label for="sort_order">Sort Order</label>
    <input type="number" name="sort_order" id="sort_order"
           class="form-control @error('sort_order') is-invalid @enderror"
           value="{{ old('sort_order', $resourceMaterial->sort_order ?? 0) }}" min="0">
    <small class="form-text text-muted">Lower numbers appear first. Default: 0.</small>
    @error('sort_order') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

{{-- Is Active --}}
<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active"
               class="custom-control-input" value="1"
               {{ old('is_active', $resourceMaterial->is_active ?? true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active (visible to students)</label>
    </div>
</div>
