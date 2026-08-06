@props(['current' => 0, 'total' => 100, 'size' => 'sm'])

@php
    $percentage = $total > 0 ? min(100, round(($current / $total) * 100)) : 0;
    $sizeClasses = $size === 'lg' ? 'h-4' : 'h-2';
@endphp

<div class="w-full {{ $sizeClasses }} bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
    <div class="h-full bg-brand-primary transition-all duration-500 ease-out rounded-full"
         style="width: {{ $percentage }}%"></div>
</div>
