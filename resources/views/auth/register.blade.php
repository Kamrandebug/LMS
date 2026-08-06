@extends('layouts.auth')

@section('title', 'Create Account — LearnUp')

@section('head')
    <meta name="robots" content="noindex">
@endsection

{{-- The flip-card layout starts flipped so the registration (back) face is shown. --}}
{{-- The layout derives the flip state from the current URL. --}}
