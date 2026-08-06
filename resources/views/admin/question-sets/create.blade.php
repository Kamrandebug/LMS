@extends('admin.layouts.app')

@section('title', 'Add Question Set')
@section('page-title', 'Add Question Set')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.question-sets.index') }}">Question Sets</a></li>
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
                    <i class="fas fa-plus-circle mr-1"></i> New Question Set
                </h3>
            </div>
            <form action="{{ route('admin.question-sets.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @include('admin.question-sets._form', [
                        'questionSet'     => null,
                        'selectedSubject' => $selectedSubject,
                        'selectedTopic'   => $selectedTopic,
                    ])
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Create Question Set
                    </button>
                    <a href="{{ route('admin.question-sets.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Tips</h3>
            </div>
            <div class="card-body text-sm">
                <ul class="pl-3 mb-0">
                    <li>Select a <strong>Subject</strong> first to filter the Topic dropdown.</li>
                    <li>The <strong>Slug</strong> auto-generates from the name — edit it manually if needed.</li>
                    <li>Slugs must be unique <em>within the same topic</em>.</li>
                    <li>You will add questions to this set after creating it.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function () {
    var allTopics = @json($topics->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'subject_id' => $t->subject_id]));

    // Init Select2
    $('.select2').select2({ theme: 'bootstrap4' });

    // Subject → filter Topic dropdown
    function filterTopics(subjectId, keepValue) {
        var $topicSel = $('#topic_id');
        $topicSel.empty().append('<option value="">— Select Topic —</option>');

        $.each(allTopics, function (_, topic) {
            if (!subjectId || topic.subject_id == subjectId) {
                $topicSel.append($('<option>', { value: topic.id, text: topic.name }));
            }
        });

        if (keepValue) {
            $topicSel.val(keepValue).trigger('change.select2');
        }
    }

    $('#subject_id_ui').on('change', function () {
        filterTopics($(this).val(), null);
    });

    // On page load — if a subject is pre-selected, filter topics and restore topic selection
    var preSubject = $('#subject_id_ui').val();
    var preTopic   = '{{ $selectedTopic ?? "" }}';
    if (preSubject) {
        filterTopics(preSubject, preTopic || null);
    }

    // Name → slug auto-generate
    $('#name').on('input', function () {
        var slug = $(this).val()
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s\-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });
});
</script>
@endpush
