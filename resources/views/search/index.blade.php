@extends('layouts.app')

@section('title', $query ? "Search results for '{$query}'" : 'Search Articles')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-4">
                @if ($query)
                    Search Results
                @else
                    Search Articles
                @endif
            </h1>

            @if ($query)
                <p class="text-gray-600 text-lg">
                    Found <strong>{{ $total }}</strong> {{ Str::plural('result', $total) }} for
                    "<strong>{{ $query }}</strong>"
                </p>
            @endif
        </div>

        <!-- Search Form -->
        <div class="mb-12">
            <form action="{{ route('search') }}" method="GET" class="max-w-3xl">
                <div class="flex gap-2">
                    <input type="text" name="q" value="{{ $query }}"
                        placeholder="Search articles, categories, tags..."
                        class="flex-1 px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none"
                        autofocus>
                    <button type="submit"
                        class="bg-red-600 text-white px-8 py-4 rounded-lg font-bold hover:bg-red-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        @if ($query)
            @if ($articles->count() > 0)
                <!-- Search Results -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach ($articles as $article)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                            <a href="{{ route('article.show', $article->slug) }}" class="block">
                                @if ($article->getFirstMediaUrl('featured_image'))
                                    <img src="{{ $article->getFirstMediaUrl('featured_image') }}"
                                        alt="{{ $article->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span
                                            class="inline-block bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
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

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $articles->appends(['q' => $query])->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-600 mb-2">No results found</h2>
                    <p class="text-gray-500 mb-6">
                        We couldn't find any articles matching "<strong>{{ $query }}</strong>"
                    </p>
                    <a href="{{ route('home') }}"
                        class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700 transition">
                        Browse All Articles
                    </a>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">Start Searching</h2>
                <p class="text-gray-500">
                    Enter keywords to search our articles
                </p>
            </div>
        @endif
    </div>
@endsection
