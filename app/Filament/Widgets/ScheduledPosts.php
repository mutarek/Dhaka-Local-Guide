<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ScheduledPosts extends TableWidget
{
    protected static ?string $heading = 'Content calendar';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->where('status', Post::STATUS_SCHEDULED)->orderBy('published_at'))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('published_at')->label('Scheduled for')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
