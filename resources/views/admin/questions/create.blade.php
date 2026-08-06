@extends('admin.layouts.app')

@section('title', 'Add Question')
@section('page-title', 'Add Question')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-lg-9 col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle mr-1"></i> New Question
                </h3>
            </div>
            <form action="{{ route('admin.questions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @include('admin.questions._form', [
                        'question'       => null,
                        'selectedSubject' => $selectedSubject,
                        'selectedTopic'   => $selectedTopic,
                        'selectedSet'     => $selectedSet,
                    ])
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Question
                    </button>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-3 col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Tips</h3>
            </div>
            <div class="card-body text-sm">
                <ul class="pl-3 mb-0">
                    <li>Select <strong>Subject → Topic → Set</strong> first.</li>
                    <li>All 4 options are required.</li>
                    <li>Click the <strong class="text-success">radio button</strong> to mark the correct answer.</li>
                    <li>The correct option label turns <strong class="text-success">green</strong>.</li>
                    <li>Explanation is optional but helps students learn.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
