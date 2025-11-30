@extends('layouts.app')

@section('title', 'The Good Beacon News - Breaking News & Latest Updates')

@push('head')
    <!-- Schema.org structured data for Website -->
    <script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "The Good Beacon News",
  "description": "Your trusted source for breaking news, in-depth analysis, and expert opinions on world events, politics, technology, and more.",
  "url": "{{ route('home') }}",
  "potentialAction": {
    "@@type": "SearchAction",
    "target": "{{ route('home') }}?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

    <!-- Schema.org structured data for Organization -->
    <script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsMediaOrganization",
  "name": "The Good Beacon News",
  "url": "{{ route('home') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "sameAs": [
    "https://twitter.com/goodbeaconnews",
    "https://facebook.com/goodbeaconnews",
    "https://instagram.com/goodbeaconnews"
  ]
}
</script>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:title" content="The Good Beacon News - Breaking News & Latest Updates">
    <meta property="og:description"
        content="Stay informed with The Good Beacon News. Breaking news, in-depth analysis, and expert opinions on world events.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('home') }}">
    <meta name="twitter:title" content="The Good Beacon News - Breaking News & Latest Updates">
    <meta name="twitter:description"
        content="Stay informed with The Good Beacon News. Breaking news, in-depth analysis, and expert opinions on world events.">
    <meta name="twitter:image" content="{{ asset('images/twitter-card.jpg') }}">
@endpush

@section('content')
    <!-- Breaking News Banner -->
    @if ($featuredArticles->count() > 0)
        <div class="bg-black text-white py-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <span class="bg-red-600 px-3 py-1 text-xs font-bold mr-4">BREAKING</span>
                    <marquee class="text-sm">
                        @foreach ($featuredArticles as $article)
                            <a href="{{ route('article.show', $article->slug) }}" class="hover:underline mr-8">
                                {{ $article->title }}
                            </a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        @if ($featuredArticles->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                <!-- Main Featured Article -->
                <div class="lg:col-span-2">
                    <a href="{{ route('article.show', $featuredArticles[0]->slug) }}"
                        class="group block relative overflow-hidden rounded-lg">
                        @if ($featuredArticles[0]->getFirstMediaUrl('featured_image'))
                            <img src="{{ $featuredArticles[0]->getFirstMediaUrl('featured_image') }}"
                                alt="{{ $featuredArticles[0]->title }}"
                                class="w-full h-[500px] object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div
                                class="w-full h-[500px] bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                                <span class="text-white text-6xl font-bold opacity-30">News</span>
                            </div>
                        @endif

                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-6">
                            <span class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded mb-2">
                                {{ $featuredArticles[0]->category->name }}
                            </span>
                            <h2 class="text-white text-3xl font-bold mb-2 group-hover:text-red-400 transition">
                                {{ $featuredArticles[0]->title }}
                            </h2>
                            <p class="text-gray-300 text-sm line-clamp-2">
                                {{ $featuredArticles[0]->excerpt }}
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Side Featured Articles -->
                <div class="space-y-6">
                    @foreach ($featuredArticles->skip(1)->take(2) as $article)
                        <a href="{{ route('article.show', $article->slug) }}" class="group block">
                            <div class="relative overflow-hidden rounded-lg">
                                @if ($article->getFirstMediaUrl('featured_image'))
                                    <img src="{{ $article->getFirstMediaUrl('featured_image') }}"
                                        alt="{{ $article->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-400 to-gray-600"></div>
                                @endif

                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <span
                                        class="inline-block bg-red-600 text-white text-xs font-bold px-2 py-1 rounded mb-1">
                                        {{ $article->category->name }}
                                    </span>
                                    <h3
                                        class="text-white font-bold text-lg line-clamp-2 group-hover:text-red-400 transition">
                                        {{ $article->title }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Latest News Section -->
        <div class="border-t-4 border-red-600 pt-8 mb-8">
            <h2 class="text-3xl font-bold mb-6">Latest News</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($latestArticles as $article)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                        <a href="{{ route('article.show', $article->slug) }}" class="block">
                            @if ($article->getFirstMediaUrl('featured_image'))
                                <img src="{{ $article->getFirstMediaUrl('featured_image') }}" alt="{{ $article->title }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div
                                    class="w-full h-48 bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                            @endif

                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-block bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                        {{ $article->category->name }}
                                    </span>
                                    <span class="text-gray-500 text-xs">
                                        {{ $article->published_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h3 class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-red-600 transition">
                                    {{ $article->title }}
                                </h3>

                                <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                    {{ $article->excerpt }}
                                </p>

                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $article->user->name }}

                                    <span class="mx-2">•</span>

                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ number_format($article->views) }} views
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-3xl font-bold mb-6">Browse by Category</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}"
                        class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition text-center group">
                        <h3 class="font-bold text-lg group-hover:text-red-600 transition">
                            {{ $category->name }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ $category->articles()->published()->count() }} articles
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
