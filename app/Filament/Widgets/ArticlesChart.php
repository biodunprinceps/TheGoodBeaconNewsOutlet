<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ArticlesChart extends ChartWidget
{
    protected static ?string $heading = 'Articles Published (Last 12 Months)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = $this->getArticlesPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Articles Published',
                    'data' => $data['counts'],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',  // Blue
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getArticlesPerMonth(): array
    {
        $months = [];
        $counts = [];

        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Article::where('status', 'published')
                ->whereYear('published_at', $date->year)
                ->whereMonth('published_at', $date->month)
                ->count();

            $counts[] = $count;
        }

        return [
            'months' => $months,
            'counts' => $counts,
        ];
    }
}
