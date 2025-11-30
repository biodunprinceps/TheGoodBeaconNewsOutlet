<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'The Good Beacon News - Breaking News & Latest Updates')</title>
    <meta name="description" content="@yield('meta_description', 'Stay informed with The Good Beacon News. Breaking news, in-depth analysis, and expert opinions on world events, politics, technology, and more.')">
    <meta name="keywords" content="@yield('meta_keywords', 'news, breaking news, world news, politics, technology, business')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cnn-sans:400,600,700|inter:400,500,600,700&display=swap"
        rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback: Use CDN Tailwind CSS when Vite build is missing -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            /* Basic CNN-style styling fallback */
            body {
                font-family: 'Inter', system-ui, sans-serif;
            }

            .font-cnn {
                font-family: 'CNN Sans', 'Inter', system-ui, sans-serif;
            }
        </style>
    @endif

    @stack('head')
</head>

<body class="antialiased bg-gray-50 text-gray-900">
    <!-- Top Banner -->
    <div class="bg-red-600 text-white text-xs py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <span class="font-semibold">{{ now()->format('l, F j, Y') }}</span>
                <span>Trusted News Source</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-red-600">
                        The Good Beacon
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-700 hover:text-red-600 font-medium transition">Home</a>
                    @foreach (\App\Models\Category::where('is_active', true)->orderBy('order')->take(6)->get() as $category)
                        <a href="{{ route('category.show', $category->slug) }}"
                            class="text-gray-700 hover:text-red-600 font-medium transition">
                            {{ $category->name }}
                        </a>
                    @endforeach

                    <!-- Search Button -->
                    <a href="{{ route('search') }}" class="text-gray-700 hover:text-red-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center gap-4">
                    <!-- Mobile Search Button -->
                    <a href="{{ route('search') }}" class="text-gray-700 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </a>

                    <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-red-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50">Home</a>
                @foreach (\App\Models\Category::where('is_active', true)->orderBy('order')->get() as $category)
                    <a href="{{ route('category.show', $category->slug) }}"
                        class="block px-3 py-2 text-gray-700 hover:bg-gray-50">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-white text-xl font-bold mb-4">The Good Beacon News</h3>
                    <p class="text-gray-400 mb-4">
                        Your trusted source for breaking news, in-depth analysis, and expert opinions on world events.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition">Twitter</a>
                        <a href="#" class="hover:text-white transition">Facebook</a>
                        <a href="#" class="hover:text-white transition">Instagram</a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Categories</h4>
                    <ul class="space-y-2">
                        @foreach (\App\Models\Category::where('is_active', true)->orderBy('order')->take(5)->get() as $category)
                            <li>
                                <a href="{{ route('category.show', $category->slug) }}"
                                    class="hover:text-white transition">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">About</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} The Good Beacon News. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
