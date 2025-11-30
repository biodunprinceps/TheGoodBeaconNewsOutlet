<x-mail::message>
    # New Article Published

    **{{ $article->title }}**

    {{ $article->excerpt }}

    ---

    **Category:** {{ $article->category->name }}
    **Author:** {{ $article->user->name }}
    **Published:** {{ $article->published_at->format('F j, Y') }}

    <x-mail::button :url="$url">
        Read Full Article
    </x-mail::button>

    Stay informed with The Good Beacon News.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
