<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
  public function index()
  {
    $sitemap = Sitemap::create();

    // Add homepage
    $sitemap->add(
      Url::create(route('home'))
        ->setLastModificationDate(now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(1.0)
    );

    // Add categories
    Category::where('is_active', true)->each(function (Category $category) use ($sitemap) {
      $sitemap->add(
        Url::create(route('category.show', $category->slug))
          ->setLastModificationDate($category->updated_at)
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
          ->setPriority(0.8)
      );
    });

    // Add articles
    Article::published()
      ->with('category')
      ->each(function (Article $article) use ($sitemap) {
        $sitemap->add(
          Url::create(route('article.show', $article->slug))
            ->setLastModificationDate($article->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.9)
        );
      });

    return $sitemap->toResponse(request());
  }
}
