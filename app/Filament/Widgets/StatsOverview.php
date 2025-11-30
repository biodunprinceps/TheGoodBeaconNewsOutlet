<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $draftArticles = Article::where('status', 'draft')->count();
        $totalViews = Article::sum('views');

        // Articles published this month
        $thisMonthArticles = Article::where('status', 'published')
            ->whereYear('published_at', now()->year)
            ->whereMonth('published_at', now()->month)
            ->count();

        // Last month articles for comparison
        $lastMonthArticles = Article::where('status', 'published')
            ->whereYear('published_at', now()->subMonth()->year)
            ->whereMonth('published_at', now()->subMonth()->month)
            ->count();

        $articlesTrend = $lastMonthArticles > 0
            ? round((($thisMonthArticles - $lastMonthArticles) / $lastMonthArticles) * 100, 1)
            : 0;

        return [
            Stat::make('Total Articles', $totalArticles)
                ->description($publishedArticles . ' published, ' . $draftArticles . ' drafts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart([7, 12, 15, 18, 22, 25, $totalArticles]),

            Stat::make('Published This Month', $thisMonthArticles)
                ->description($articlesTrend >= 0 ? "+{$articlesTrend}% from last month" : "{$articlesTrend}% from last month")
                ->descriptionIcon($articlesTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($articlesTrend >= 0 ? 'success' : 'danger'),

            Stat::make('Total Views', number_format($totalViews))
                ->description('Across all articles')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('Categories', Category::count())
                ->description(Category::where('is_active', true)->count() . ' active')
                ->descriptionIcon('heroicon-m-folder')
                ->color('warning'),

            Stat::make('Tags', Tag::count())
                ->description('Available tags')
                ->descriptionIcon('heroicon-m-tag')
                ->color('success'),

            Stat::make('Authors', User::count())
                ->description('Content creators')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
