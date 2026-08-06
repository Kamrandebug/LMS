@extends('admin.layouts.app')

@section('title', 'Edit Question Set')
@section('page-title', 'Edit Question Set')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.question-sets.index') }}">Question Sets</a></li>
    <li class="breadcrumb-item active">Edit: {{ $questionSet->name }}</li>
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
                    <i class="fas fa-edit mr-1"></i> Edit: {{ $questionSet->name }}
                </h3>
            </div>
            <form action="{{ route('admin.question-sets.update', $questionSet) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('admin.question-sets._form', [
                        'questionSet'     => $questionSet,
                        'selectedSubject' => null,
                        'selectedTopic'   => null,
                    ])
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Question Set
                        </button>
                        <a href="{{ route('admin.question-sets.index', ['topic_id' => $questionSet->topic_id]) }}"
                           class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    <small class="text-muted">
                        ID: {{ $questionSet->id }} &nbsp;|&nbsp;
                        Created: {{ $questionSet->created_at->format('d M Y') }}
                    </small>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4 col-12">

        {{-- Stats Card --}}
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Set Stats</h3>
            </div>
            <div class="card-body">
                @php $questionsCount = $questionSet->questions()->count(); @endphp
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Subject</td>
                        <td>
                            <span class="badge badge-info">
                                {!! $questionSet->topic->subject->icon_svg ?? '' !!}
                                {{ $questionSet->topic->subject->name ?? '—' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Topic</td>
                        <td>{{ $questionSet->topic->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Questions</td>
                        <td>
                            <span class="badge badge-{{ $questionsCount > 0 ? 'success' : 'secondary' }}">
                                {{ $questionsCount }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Slug</td>
                        <td><code class="small">{{ $questionSet->slug }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($questionSet->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title text-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Danger Zone
                </h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Deletion is blocked if this set has questions.
                    Remove all questions first.
                </p>
                <form action="{{ route('admin.question-sets.destroy', $questionSet) }}"
                      method="POST" id="deleteSetForm">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            id="deleteSetBtn"
                            data-questions="{{ $questionsCount }}"
                            data-name="{{ addslashes($questionSet->name) }}">
                        <i class="fas fa-trash mr-1"></i> Delete This Set
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

    // On load — restore existing subject/topic selection
    var existingSubject = $('#subject_id_ui').val();
    var existingTopic   = {{ $questionSet->topic_id }};
    filterTopics(existingSubject, existingTopic);

    // Re-generate slug button
    $('#regenSlugBtn').on('click', function () {
        var slug = $('#name').val()
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s\-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    });

    // Delete SweetAlert
    $('#deleteSetBtn').on('click', function () {
        var questions = parseInt($(this).data('questions'));
        var name      = $(this).data('name');

        if (questions > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete',
                html: '<b>' + name + '</b> has <b>' + questions + ' question(s)</b>.<br>Delete all questions first.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6c757d',
            });
            return;
        }

        Swal.fire({
            title: 'Delete Question Set?',
            html: 'Delete <b>' + name + '</b> permanently?<br><small class="text-muted">This cannot be undone.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('deleteSetForm').submit();
            }
        });
    });
});
</script>
@endpush
