{{-- NNCLOTHING Logo Component --}}
@props([
    'type' => 'full', // full, icon, horizontal, premium, minimalist
    'size' => 'md',   // sm, md, lg, xl, xxl
    'link' => null,   // URL to link to
    'class' => ''     // Additional CSS classes
])

@php
    $sizeMap = [
        'sm' => 'w-24 h-auto',
        'md' => 'w-40 h-auto',
        'lg' => 'w-56 h-auto',
        'xl' => 'w-72 h-auto',
        'xxl' => 'w-full max-w-2xl h-auto',
    ];
    
    $logoFiles = [
        'full' => 'nnclothing-full-logo.svg',
        'icon' => 'nnclothing-icon.svg',
        'horizontal' => 'nnclothing-horizontal.svg',
        'premium' => 'nnclothing-premium.svg',
        'minimalist' => 'nnclothing-minimalist.svg',
    ];
    
    $logoFile = $logoFiles[$type] ?? 'nnclothing-full-logo.svg';
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div class="{{ $class }}">
    @if ($link)
        <a href="{{ $link }}" class="inline-block hover:opacity-80 transition-opacity duration-200">
            <img 
                src="{{ asset('assets/logos/' . $logoFile) }}" 
                alt="NNCLOTHING Logo"
                class="{{ $sizeClass }}"
            />
        </a>
    @else
        <img 
            src="{{ asset('assets/logos/' . $logoFile) }}" 
            alt="NNCLOTHING Logo"
            class="{{ $sizeClass }}"
        />
    @endif
</div>
