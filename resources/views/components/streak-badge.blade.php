@props(['count' => 0])

@if($count > 0)
    <span class="inline-flex items-center gap-1 text-sm font-bold text-orange-500">
        🔥 {{ $count }}
    </span>
@endif
