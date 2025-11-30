<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index()
  {
    $featuredArticles = Article::published()
      ->featured()
      ->with(['category', 'user'])
      ->latest('published_at')
      ->take(5)
      ->get();

    $latestArticles = Article::published()
      ->with(['category', 'user'])
      ->latest('published_at')
      ->take(12)
      ->get();

    $categories = Category::where('is_active', true)
      ->orderBy('order')
      ->get();

    return view('home', compact('featuredArticles', 'latestArticles', 'categories'));
  }
}
