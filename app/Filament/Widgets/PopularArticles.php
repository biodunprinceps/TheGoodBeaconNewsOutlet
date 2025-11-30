<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularArticles extends BaseWidget
{
  protected static ?int $sort = 4;

  protected int | string | array $columnSpan = 'full';

  public function table(Table $table): Table
  {
    return $table
      ->query(
        Article::query()
          ->with(['category', 'user'])
          ->where('status', 'published')
          ->orderBy('views', 'desc')
          ->limit(10)
      )
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('Title')
          ->searchable()
          ->limit(50)
          ->url(fn(Article $record): string => route('articles.show', $record->slug), shouldOpenInNewTab: true),

        Tables\Columns\TextColumn::make('category.name')
          ->label('Category')
          ->badge()
          ->color('info'),

        Tables\Columns\TextColumn::make('views')
          ->label('Views')
          ->numeric()
          ->sortable()
          ->icon('heroicon-m-eye')
          ->iconColor('success')
          ->weight('bold'),

        Tables\Columns\IconColumn::make('is_featured')
          ->label('Featured')
          ->boolean(),

        Tables\Columns\TextColumn::make('user.name')
          ->label('Author'),

        Tables\Columns\TextColumn::make('published_at')
          ->label('Published')
          ->dateTime('M j, Y')
          ->since(),
      ])
      ->heading('Most Popular Articles')
      ->description('Top 10 most viewed published articles')
      ->defaultSort('views', 'desc');
  }
}
