<?php

/**
 * NNCLOTHING Logo Configuration
 * 
 * Configuration file untuk branding NNCLOTHING
 * Semua pengaturan logo dapat diakses dari file ini
 */

return [
    /**
     * Logo Configuration
     */
    'logo' => [
        /**
         * Brand Name
         */
        'brand_name' => 'NNCLOTHING',
        'brand_tagline' => 'Premium Custom Apparel',
        'brand_full_tagline' => 'Custom Apparel Crafted with Excellence',

        /**
         * Logo Assets Path
         */
        'assets_path' => 'assets/logos',

        /**
         * Logo Files
         */
        'logos' => [
            'full' => [
                'file' => 'nnclothing-full-logo.svg',
                'width' => '400',
                'height' => '120',
                'aspect_ratio' => '3.33:1',
                'best_for' => ['hero', 'main_header', 'landing_page'],
                'description' => 'Full logo with brand name and tagline'
            ],
            'icon' => [
                'file' => 'nnclothing-icon.svg',
                'width' => '120',
                'height' => '120',
                'aspect_ratio' => '1:1',
                'best_for' => ['favicon', 'app_icon', 'social_media', 'profile_picture'],
                'description' => 'Icon only - Double N monogram'
            ],
            'horizontal' => [
                'file' => 'nnclothing-horizontal.svg',
                'width' => '300',
                'height' => '80',
                'aspect_ratio' => '3.75:1',
                'best_for' => ['navigation', 'sidebar', 'compact_header'],
                'description' => 'Icon and text side by side'
            ],
            'premium' => [
                'file' => 'nnclothing-premium.svg',
                'width' => '400',
                'height' => '200',
                'aspect_ratio' => '2:1',
                'best_for' => ['print', 'business_card', 'presentation', 'poster'],
                'description' => 'Detailed logo with t-shirt silhouette'
            ],
            'minimalist' => [
                'file' => 'nnclothing-minimalist.svg',
                'width' => '100',
                'height' => '100',
                'aspect_ratio' => '1:1',
                'best_for' => ['mobile_app', 'mobile_nav', 'compact_spaces'],
                'description' => 'Clean and minimal icon design'
            ]
        ],

        /**
         * Size Presets (Tailwind CSS widths)
         */
        'sizes' => [
            'sm' => [
                'value' => 'w-24',
                'pixels' => '96px',
                'use_cases' => ['mobile_menu', 'favicon', 'compact_logo']
            ],
            'md' => [
                'value' => 'w-40',
                'pixels' => '160px',
                'use_cases' => ['navigation', 'footer', 'standard']
            ],
            'lg' => [
                'value' => 'w-56',
                'pixels' => '224px',
                'use_cases' => ['hero_section', 'main_header', 'large_display']
            ],
            'xl' => [
                'value' => 'w-72',
                'pixels' => '288px',
                'use_cases' => ['premium_sections', 'large_display', 'print']
            ],
            'xxl' => [
                'value' => 'w-full max-w-2xl',
                'pixels' => 'responsive',
                'use_cases' => ['full_width', 'banner', 'poster']
            ]
        ],

        /**
         * Brand Colors
         */
        'colors' => [
            'primary_blue' => '#3b82f6',
            'dark_blue' => '#1e40af',
            'charcoal' => '#1f2937',
            'dark_gray' => '#111827',
            'medium_gray' => '#6b7280',
            'light_gray' => '#f3f4f6',
        ],

        /**
         * Recommended Sizes by Context
         */
        'context_sizes' => [
            'favicon' => ['size' => 'sm', 'type' => 'icon'],
            'mobile_menu' => ['size' => 'sm', 'type' => 'icon'],
            'navigation_bar' => ['size' => 'md', 'type' => 'horizontal'],
            'footer' => ['size' => 'md', 'type' => 'icon'],
            'hero_section' => ['size' => 'lg', 'type' => 'full'],
            'sidebar' => ['size' => 'md', 'type' => 'icon'],
            'business_card' => ['size' => 'lg', 'type' => 'premium'],
            'social_media' => ['size' => 'sm', 'type' => 'icon'],
            'poster' => ['size' => 'xxl', 'type' => 'premium'],
        ]
    ],

    /**
     * Brand Identity Settings
     */
    'brand' => [
        'name' => 'NNCLOTHING',
        'legal_name' => 'NN Clothing Indonesia',
        'description' => 'Platform untuk custom apparel berkualitas premium dengan desain modern',
        'url' => env('APP_URL', 'http://localhost:8000'),
        'support_email' => env('MAIL_FROM_ADDRESS', 'support@nnclothing.id'),
        'social' => [
            'instagram' => '@nnclothing',
            'facebook' => 'nnclothing',
            'tiktok' => '@nnclothing',
            'whatsapp' => env('WHATSAPP_NUMBER', '+62812xxxx'),
        ]
    ],

    /**
     * SEO & Meta Tags
     */
    'seo' => [
        'og_image' => 'assets/logos/nnclothing-full-logo.svg',
        'favicon' => 'assets/logos/nnclothing-icon.svg',
        'apple_touch_icon' => 'assets/logos/nnclothing-icon.svg',
    ]
];
