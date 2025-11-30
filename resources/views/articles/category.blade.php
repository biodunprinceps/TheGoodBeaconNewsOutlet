@extends('layouts.app')

@section('title', $category->name . ' - The Good Beacon News')
@section('meta_description', $category->description)

@section('content')
    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm mb-4">
                <a href="{{ route('home') }}" class="hover:underline">Home</a>
                <span class="mx-2">/</span>
                <span>{{ $category->name }}</span>
            </nav>

            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                {{ $category->name }}
            </h1>

            @if ($category->description)
                <p class="text-xl text-red-100">
                    {{ $category->description }}
                </p>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if ($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($articles as $article)
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

                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-gray-500 text-sm">
                                        {{ $article->published_at->format('M j, Y') }}
                                    </span>
                                    <span class="text-gray-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($article->views) }}
                                    </span>
                                </div>

                                <h2 class="font-bold text-xl mb-3 line-clamp-2 group-hover:text-red-600 transition">
                                    {{ $article->title }}
                                </h2>

                                <p class="text-gray-600 text-sm line-clamp-3 mb-3">
                                    {{ $article->excerpt }}
                                </p>

                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $article->user->name }}
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No articles found</h3>
                <p class="text-gray-500">Check back soon for updates in this category.</p>
                <a href="{{ route('home') }}"
                    class="inline-block mt-4 bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">
                    Back to Home
                </a>
            </div>
        @endif
    </div>
@endsection
