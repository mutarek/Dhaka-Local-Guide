<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopViewedPosts extends TableWidget
{
    protected static ?string $heading = 'Top 10 most viewed posts';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->orderByDesc('views_count')->limit(10))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('views_count')->label('Views')->numeric()->sortable(),
                TextColumn::make('published_at')->date()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
