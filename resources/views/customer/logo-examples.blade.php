{{-- Example usage of NNCLOTHING Logo Component --}}

@extends('layouts.customer')

@section('customer-content')
    <div class="container mx-auto px-4 py-12 max-w-6xl">
        <!-- Header with Logo -->
        <div class="mb-16 text-center">
            <div class="flex justify-center mb-8">
                <x-nnclothing-logo type="icon" size="lg" />
            </div>
            <h1 class="text-5xl font-bold text-gray-900 mb-2">NNCLOTHING</h1>
            <p class="text-lg text-gray-600">Premium Custom Apparel Platform</p>
        </div>

        <!-- Logo Variations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Full Logo -->
            <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Full Logo</h3>
                <div class="bg-gray-50 p-6 rounded-lg flex items-center justify-center min-h-[200px] mb-4 border-2 border-dashed border-gray-300">
                    <x-nnclothing-logo type="full" size="md" />
                </div>
                <p class="text-sm text-gray-600">Use for main headers and hero sections</p>
                <code class="block mt-3 p-2 bg-gray-100 text-gray-800 text-xs rounded">
                    &lt;x-nnclothing-logo type="full" size="md" /&gt;
                </code>
            </div>

            <!-- Icon Logo -->
            <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Icon Logo</h3>
                <div class="bg-gray-50 p-6 rounded-lg flex items-center justify-center min-h-[200px] mb-4 border-2 border-dashed border-gray-300">
                    <x-nnclothing-logo type="icon" size="md" />
                </div>
                <p class="text-sm text-gray-600">Perfect for favicon and app icons</p>
                <code class="block mt-3 p-2 bg-gray-100 text-gray-800 text-xs rounded">
                    &lt;x-nnclothing-logo type="icon" size="md" /&gt;
                </code>
            </div>

            <!-- Horizontal Logo -->
            <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Horizontal Logo</h3>
                <div class="bg-gray-50 p-6 rounded-lg flex items-center justify-center min-h-[200px] mb-4 border-2 border-dashed border-gray-300">
                    <x-nnclothing-logo type="horizontal" size="sm" />
                </div>
                <p class="text-sm text-gray-600">Ideal for navigation bars</p>
                <code class="block mt-3 p-2 bg-gray-100 text-gray-800 text-xs rounded">
                    &lt;x-nnclothing-logo type="horizontal" size="sm" /&gt;
                </code>
            </div>

            <!-- Premium Logo -->
            <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Premium Logo</h3>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg flex items-center justify-center min-h-[200px] mb-4 border-2 border-dashed border-blue-300">
                    <x-nnclothing-logo type="premium" size="sm" />
                </div>
                <p class="text-sm text-gray-600">Great for presentations</p>
                <code class="block mt-3 p-2 bg-gray-100 text-gray-800 text-xs rounded">
                    &lt;x-nnclothing-logo type="premium" size="sm" /&gt;
                </code>
            </div>

            <!-- Minimalist Logo -->
            <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Minimalist Logo</h3>
                <div class="bg-gray-50 p-6 rounded-lg flex items-center justify-center min-h-[200px] mb-4 border-2 border-dashed border-gray-300">
                    <x-nnclothing-logo type="minimalist" size="md" />
                </div>
                <p class="text-sm text-gray-600">Compact and clean design</p>
                <code class="block mt-3 p-2 bg-gray-100 text-gray-800 text-xs rounded">
                    &lt;x-nnclothing-logo type="minimalist" size="md" /&gt;
                </code>
            </div>

            <!-- With Link -->
            <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Logo with Link</h3>
                <div class="bg-gray-50 p-6 rounded-lg flex items-center justify-center min-h-[200px] mb-4 border-2 border-dashed border-gray-300">
                    <x-nnclothing-logo type="horizontal" size="sm" link="/" />
                </div>
                <p class="text-sm text-gray-600">Clickable logo for navigation</p>
                <code class="block mt-3 p-2 bg-gray-100 text-gray-800 text-xs rounded">
                    &lt;x-nnclothing-logo type="horizontal" size="sm" link="/" /&gt;
                </code>
            </div>
        </div>

        <!-- Size Variations -->
        <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100 mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Size Variations</h2>
            <div class="space-y-8">
                <div class="border-b pb-4">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Small (sm) - w-24</p>
                    <div class="bg-gray-50 p-4 rounded-lg inline-block">
                        <x-nnclothing-logo type="icon" size="sm" />
                    </div>
                </div>
                <div class="border-b pb-4">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Medium (md) - w-40</p>
                    <div class="bg-gray-50 p-4 rounded-lg inline-block">
                        <x-nnclothing-logo type="icon" size="md" />
                    </div>
                </div>
                <div class="border-b pb-4">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Large (lg) - w-56</p>
                    <div class="bg-gray-50 p-4 rounded-lg inline-block">
                        <x-nnclothing-logo type="icon" size="lg" />
                    </div>
                </div>
                <div class="border-b pb-4">
                    <p class="text-sm font-semibold text-gray-600 mb-3">Extra Large (xl) - w-72</p>
                    <div class="bg-gray-50 p-4 rounded-lg inline-block">
                        <x-nnclothing-logo type="icon" size="xl" />
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-3">Extra Extra Large (xxl) - Full Width</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <x-nnclothing-logo type="horizontal" size="xxl" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Implementation Examples -->
        <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Implementation Examples</h2>
            
            <!-- Navigation Example -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">1. In Navigation Bar</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;nav class="navbar"&gt;
    &lt;x-nnclothing-logo type="horizontal" size="sm" link="/" /&gt;
    &lt;!-- Navigation items --&gt;
&lt;/nav&gt;</code></pre>
            </div>

            <!-- Footer Example -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">2. In Footer</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;footer class="bg-gray-900 text-white"&gt;
    &lt;div class="flex items-center gap-4"&gt;
        &lt;x-nnclothing-logo type="icon" size="sm" /&gt;
        &lt;p&gt;&copy; 2024 NNCLOTHING. All rights reserved.&lt;/p&gt;
    &lt;/div&gt;
&lt;/footer&gt;</code></pre>
            </div>

            <!-- Hero Section Example -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">3. In Hero Section</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;section class="hero-section text-center"&gt;
    &lt;x-nnclothing-logo type="full" size="lg" /&gt;
    &lt;h1&gt;Welcome to NNCLOTHING&lt;/h1&gt;
    &lt;p&gt;Premium Custom Apparel&lt;/p&gt;
&lt;/section&gt;</code></pre>
            </div>

            <!-- With Custom Styling -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">4. With Custom Styling</h3>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;x-nnclothing-logo 
    type="icon" 
    size="md" 
    class="hover:shadow-lg hover:scale-105 transition-all" /&gt;</code></pre>
            </div>
        </div>
    </div>
@endsection
