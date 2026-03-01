{{-- NNCLOTHING Navbar Logo - Horizontal Format (38px height) --}}
@props(['size' => 'sm'])

<!-- Horizontal Logo: Icon + Brand Name (Compact for Navbar) -->
<div class="h-10 flex items-center gap-1.5 transition-transform duration-300 hover:scale-105" {{ $attributes }}>
    <svg viewBox="0 0 300 80" xmlns="http://www.w3.org/2000/svg" class="h-full w-auto" preserveAspectRatio="xMidYMid meet">
        <defs>
            <linearGradient id="navbarHorzGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#1e40af;stop-opacity:1" />
            </linearGradient>
        </defs>
        
        <!-- Icon Circle (Left) -->
        <circle cx="25" cy="40" r="22" fill="url(#navbarHorzGradient)" />
        <text x="25" y="50" font-family="Arial, sans-serif" font-size="30" font-weight="900" fill="white" text-anchor="middle" letter-spacing="1">NN</text>
        
        <!-- Vertical Divider Line -->
        <line x1="52" y1="15" x2="52" y2="65" stroke="#e5e7eb" stroke-width="1.5" opacity="0.6" />
        
        <!-- Brand Name (Right) -->
        <text x="65" y="48" font-family="Arial, sans-serif" font-size="20" font-weight="900" fill="#3b82f6" letter-spacing="0.2">NNCLOTHING</text>
    </svg>
</div>
