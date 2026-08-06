{{-- SEO meta tags are handled via SEOTools in the controller and rendered in layouts/app.blade.php --}}
{{-- This component is kept for specialized overrides per page --}}
@props(['title' => null, 'description' => null, 'image' => null])

@if($title)
    <title>{{ $title }} | LearnUp</title>
@endif
@if($description)
    <meta name="description" content="{{ $description }}">
@endif
@if($image)
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:image" content="{{ $image }}">
@else
    <meta property="og:image" content="{{ asset('images/og-default.png') }}">
    <meta name="twitter:image" content="{{ asset('images/og-default.png') }}">
@endif

<meta property="og:type" content="website">
<meta property="twitter:card" content="summary_large_image">
