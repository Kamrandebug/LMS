{{--
    Shared form partial for Question create and edit.
    Variables expected:
      $subjects        — Collection of subjects
      $topics          — Collection of all topics
      $questionSets    — Collection of all question sets
      $question        — Question model (null on create, populated on edit)
      $selectedSubject — int|null
      $selectedTopic   — int|null
      $selectedSet     — int|null
--}}

@php
    $isEdit         = isset($question) && $question->exists;
    $oldSetId       = old('question_set_id', $isEdit ? $question->question_set_id : $selectedSet);
    $oldTopicId     = old('topic_id_ui',     $isEdit ? ($question->questionSet->topic_id ?? null) : $selectedTopic);
    $oldSubjectId   = old('subject_id_ui',   $isEdit ? ($question->questionSet->topic->subject_id ?? null) : $selectedSubject);
    $oldCorrect     = old('correct_option',  $isEdit ? $question->correct_option : null);
@endphp

{{-- ======= 3-LEVEL CASCADE: Subject → Topic → QuestionSet ======= --}}

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Subject <span class="text-muted small">(filter only)</span></label>
            <select id="subject_id_ui" class="form-control select2 select2-bootstrap4">
                <option value="">— Select Subject —</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}"
                        {{ $oldSubjectId == $subject->id ? 'selected' : '' }}>
                        {!! $subject->icon_svg ?? '' !!} {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Topic <span class="text-muted small">(filter only)</span></label>
            <select id="topic_id_ui" class="form-control select2 select2-bootstrap4">
                <option value="">— Select Topic —</option>
                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}"
                            data-subject="{{ $topic->subject_id }}"
                        {{ $oldTopicId == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="question_set_id">
                Question Set <span class="text-danger">*</span>
            </label>
            <select id="question_set_id"
                    name="question_set_id"
                    class="form-control select2 select2-bootstrap4 @error('question_set_id') is-invalid @enderror"
                    required>
                <option value="">— Select Set —</option>
                @foreach($questionSets as $set)
                    <option value="{{ $set->id }}"
                            data-topic="{{ $set->topic_id }}"
                        {{ $oldSetId == $set->id ? 'selected' : '' }}>
                        {{ $set->name }}
                    </option>
                @endforeach
            </select>
            @error('question_set_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<hr class="mt-0 mb-3">

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="qid">
                QID <span class="text-muted small">(optional external ID)</span>
            </label>
            <input type="text"
                   name="qid"
                   id="qid"
                   class="form-control @error('qid') is-invalid @enderror"
                   placeholder="e.g. Q-12345"
                   value="{{ old('qid', $isEdit ? $question->qid : '') }}">
            @error('qid')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

{{-- ======= QUESTION TEXT ======= --}}

<div class="form-group">
    <label for="question">
        Question Text <span class="text-danger">*</span>
    </label>
    <textarea id="question"
              name="question"
              class="form-control @error('question') is-invalid @enderror"
              rows="3"
              placeholder="Enter the full question here..."
              required>{{ old('question', $isEdit ? $question->question : '') }}</textarea>
    @error('question')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

{{-- ======= OPTIONS + CORRECT ANSWER ======= --}}

<div class="form-group">
    <label>
        Options &amp; Correct Answer
        <span class="text-danger">*</span>
        <small class="text-muted ml-2">
            <i class="fas fa-info-circle"></i>
            Click the radio button on the left to mark the correct answer.
        </small>
    </label>

    @php
        $optionLetters = ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'];
        $optionColors  = ['a' => 'primary', 'b' => 'info', 'c' => 'warning', 'd' => 'secondary'];
    @endphp

    @foreach($optionLetters as $key => $label)
    @php
        $fieldName  = 'option_' . $key;
        $oldValue   = old($fieldName, $isEdit ? $question->$fieldName : '');
        $isCorrect  = $oldCorrect === $key;
    @endphp
    <div class="option-row mb-2 {{ $isCorrect ? 'option-row--correct' : '' }}" id="optRow_{{ $key }}">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text px-3">
                    <input type="radio"
                           name="correct_option"
                           value="{{ $key }}"
                           id="correct_{{ $key }}"
                           class="correct-radio"
                           {{ $isCorrect ? 'checked' : '' }}
                           required>
                </div>
                <label class="input-group-text opt-label font-weight-bold px-3"
                       for="correct_{{ $key }}"
                       id="optLabel_{{ $key }}"
                       style="min-width:48px; justify-content:center; cursor:pointer; background:{{ $isCorrect ? '#28a745' : '' }}; color:{{ $isCorrect ? '#fff' : '' }};">
                    {{ $label }}
                </label>
            </div>
            <input type="text"
                   name="{{ $fieldName }}"
                   id="{{ $fieldName }}"
                   class="form-control option-input @error($fieldName) is-invalid @enderror"
                   placeholder="Enter option {{ $label }}..."
                   value="{{ old($fieldName, $isEdit ? $question->$fieldName : '') }}"
                   required>
        </div>
        @error($fieldName)
            <span class="text-danger small ml-1">{{ $message }}</span>
        @enderror
    </div>
    @endforeach

    @error('correct_option')
        <div class="text-danger small mt-1">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </div>
    @enderror

    <small class="text-muted">
        <i class="fas fa-check-circle text-success"></i>
        The option with the <strong class="text-success">green label</strong> is marked as correct.
    </small>
</div>

<hr class="my-3">

{{-- ======= EXPLANATION ======= --}}

<div class="form-group">
    <label for="explanation">
        Explanation
        <span class="text-muted small">(optional — shown to students after answering)</span>
    </label>
    <textarea id="explanation"
              name="explanation"
              class="form-control @error('explanation') is-invalid @enderror"
              rows="2"
              placeholder="Why is this the correct answer? (optional)">{{ old('explanation', $isEdit ? $question->explanation : '') }}</textarea>
    @error('explanation')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

{{-- ======= SORT ORDER ======= --}}

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number"
                   id="sort_order"
                   name="sort_order"
                   class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $isEdit ? $question->sort_order : '') }}"
                   min="0"
                   placeholder="0">
            <small class="text-muted">Lower = shown first. Leave blank for auto.</small>
            @error('sort_order')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- ======= IS ACTIVE ======= --}}

    <div class="col-md-4 d-flex align-items-center">
        <div class="form-group mb-0 mt-3">
            <div class="custom-control custom-switch">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       class="custom-control-input"
                       id="is_active"
                       name="is_active"
                       value="1"
                       {{ old('is_active', $isEdit ? $question->is_active : true) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">
                    Active (visible to students)
                </label>
            </div>
        </div>
    </div>
</div>

{{-- ======= CASCADE JS ======= --}}
<script>
(function() {
    var allTopics = @json($topics->map(fn($t) => ['id'=>$t->id,'name'=>$t->name,'subject_id'=>$t->subject_id]));
    var allSets   = @json($questionSets->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'topic_id'=>$s->topic_id]));

    function rebuildTopics(subjectId, keepVal) {
        var $sel = $('#topic_id_ui');
        $sel.empty().append('<option value="">— Select Topic —</option>');
        $.each(allTopics, function(_, t) {
            if (!subjectId || t.subject_id == subjectId)
                $sel.append($('<option>', {value: t.id, text: t.name}));
        });
        if (keepVal) $sel.val(keepVal).trigger('change.select2');
    }

    function rebuildSets(topicId, keepVal) {
        var $sel = $('#question_set_id');
        $sel.empty().append('<option value="">— Select Set —</option>');
        $.each(allSets, function(_, s) {
            if (!topicId || s.topic_id == topicId)
                $sel.append($('<option>', {value: s.id, text: s.name}));
        });
        if (keepVal) $sel.val(keepVal).trigger('change.select2');
    }

    $(document).ready(function() {
        $('.select2').select2({theme: 'bootstrap4'});

        $('#subject_id_ui').on('change', function() {
            rebuildTopics($(this).val(), null);
            rebuildSets(null, null);
        });
        $('#topic_id_ui').on('change', function() {
            rebuildSets($(this).val(), null);
        });

        // Restore on page load
        var preSubject = $('#subject_id_ui').val();
        var preTopic   = $('#topic_id_ui').val();
        var preSet     = $('#question_set_id').val();

        if (preSubject) rebuildTopics(preSubject, preTopic || null);
        if (preTopic)   rebuildSets(preTopic, preSet || null);

        // Correct option highlight
        function highlightCorrect(val) {
            // Reset all labels
            ['a','b','c','d'].forEach(function(k) {
                var $lbl = $('#optLabel_' + k);
                $lbl.css({background: '', color: ''});
            });
            if (val) {
                var $lbl = $('#optLabel_' + val);
                $lbl.css({background: '#28a745', color: '#fff'});
            }
        }

        // On page load
        var checked = $('input[name="correct_option"]:checked').val();
        if (checked) highlightCorrect(checked);

        // On change
        $('input.correct-radio').on('change', function() {
            highlightCorrect($(this).val());
        });
    });
})();
</script>
