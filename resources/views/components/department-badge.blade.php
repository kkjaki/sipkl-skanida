@props(['code' => '', 'showName' => false, 'name' => ''])

@php
    $badgeColors = \App\Models\Department::BADGE_COLORS[strtoupper($code)] ?? \App\Models\Department::BADGE_DEFAULT;
@endphp

<span
    {{ $attributes->merge(['class' => "inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border whitespace-nowrap {$badgeColors}"]) }}>
    {{ $showName && $name ? $name : strtoupper($code) }}
</span>
