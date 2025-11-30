<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  public function show($slug)
  {
    $article = Article::where('slug', $slug)
      ->published()
      ->with(['category', 'user', 'tags', 'media'])
      ->firstOrFail();

    // Increment views
    $article->increment('views');

    // Get related articles
    $relatedArticles = Article::published()
      ->where('category_id', $article->category_id)
      ->where('id', '!=', $article->id)
      ->latest('published_at')
      ->take(4)
      ->get();

    return view('articles.show', compact('article', 'relatedArticles'));
  }

  public function category($slug)
  {
    $category = Category::where('slug', $slug)
      ->where('is_active', true)
      ->firstOrFail();

    $articles = Article::published()
      ->where('category_id', $category->id)
      ->with(['category', 'user'])
      ->latest('published_at')
      ->paginate(12);

    return view('articles.category', compact('category', 'articles'));
  }
}
