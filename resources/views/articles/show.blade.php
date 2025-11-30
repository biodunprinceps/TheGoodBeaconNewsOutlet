@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title)
@section('meta_description', $article->meta_description ?: $article->excerpt)
@section('meta_keywords', $article->meta_keywords)

@push('head')
    <!-- Schema.org structured data for Article -->
    <script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ $article->title }}",
  "description": "{{ $article->excerpt }}",
  "image": "{{ $article->getFirstMediaUrl('featured_image') ?: asset('images/default-news.jpg') }}",
  "datePublished": "{{ $article->published_at->toIso8601String() }}",
  "dateModified": "{{ $article->updated_at->toIso8601String() }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $article->user->name }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "The Good Beacon News",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ route('article.show', $article->slug) }}"
  },
  "articleSection": "{{ $article->category->name }}",
  "keywords": "{{ $article->tags->pluck('name')->join(', ') }}"
}
</script>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('article.show', $article->slug) }}">
    <meta property="og:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?: $article->excerpt }}">
    <meta property="og:image" content="{{ $article->getFirstMediaUrl('featured_image') }}">
    <meta property="article:published_time" content="{{ $article->published_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $article->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $article->user->name }}">
    <meta property="article:section" content="{{ $article->category->name }}">
    @foreach ($article->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('article.show', $article->slug) }}">
    <meta name="twitter:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta name="twitter:description" content="{{ $article->meta_description ?: $article->excerpt }}">
    <meta name="twitter:image" content="{{ $article->getFirstMediaUrl('featured_image') }}">
@endpush

@section('content')
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-4">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('home') }}" class="text-red-600 hover:underline">Home</a>
                    <svg class="w-3 h-3 mx-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a 1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('category.show', $article->category->slug) }}" class="text-red-600 hover:underline">
                        {{ $article->category->name }}
                    </a>
                    <svg class="w-3 h-3 mx-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-500">Article</li>
            </ol>
        </nav>

        <!-- Category Badge -->
        <div class="mb-4">
            <a href="{{ route('category.show', $article->category->slug) }}"
                class="inline-block bg-red-600 text-white text-sm font-bold px-4 py-2 rounded hover:bg-red-700 transition">
                {{ $article->category->name }}
            </a>
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
            {{ $article->title }}
        </h1>

        <!-- Excerpt -->
        @if ($article->excerpt)
            <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                {{ $article->excerpt }}
            </p>
        @endif

        <!-- Meta Information -->
        <div class="flex flex-wrap items-center text-sm text-gray-600 mb-8 pb-6 border-b border-gray-200">
            <div class="flex items-center mr-6 mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>By <strong>{{ $article->user->name }}</strong></span>
            </div>

            <div class="flex items-center mr-6 mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $article->published_at->format('F j, Y') }}</span>
            </div>

            <div class="flex items-center mr-6 mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $article->published_at->diffForHumans() }}</span>
            </div>

            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ number_format($article->views) }} views</span>
            </div>
        </div>

        <!-- Featured Image -->
        @if ($article->getFirstMediaUrl('featured_image'))
            <div class="mb-8">
                <img src="{{ $article->getFirstMediaUrl('featured_image') }}" alt="{{ $article->title }}"
                    class="w-full rounded-lg shadow-lg">
            </div>
        @endif

        <!-- Article Content -->
        <div class="prose prose-lg max-w-none mb-8">
            {!! $article->content !!}
        </div>

        <!-- Tags -->
        @if ($article->tags->count() > 0)
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($article->tags as $tag)
                        <span
                            class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 transition cursor-pointer">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Share Buttons -->
        <div class="mb-12 pb-8 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Share this article:</h3>
            <div class="flex gap-3">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('article.show', $article->slug)) }}&text={{ urlencode($article->title) }}"
                    target="_blank" class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500 transition">
                    Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('article.show', $article->slug)) }}"
                    target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Facebook
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('article.show', $article->slug)) }}"
                    target="_blank" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                    LinkedIn
                </a>
            </div>
        </div>

        <!-- Related Articles -->
        @if ($relatedArticles->count() > 0)
            <div>
                <h2 class="text-2xl font-bold mb-6">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($relatedArticles as $related)
                        <a href="{{ route('article.show', $related->slug) }}"
                            class="group flex gap-4 bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
                            @if ($related->getFirstMediaUrl('featured_image'))
                                <img src="{{ $related->getFirstMediaUrl('featured_image') }}"
                                    alt="{{ $related->title }}"
                                    class="w-32 h-24 object-cover rounded group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-32 h-24 bg-gray-200 rounded flex-shrink-0"></div>
                            @endif

                            <div>
                                <h3 class="font-bold text-lg mb-1 line-clamp-2 group-hover:text-red-600 transition">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $related->published_at->format('F j, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
@endsection
