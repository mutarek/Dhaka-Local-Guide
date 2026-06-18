<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class DraftPosts extends TableWidget
{
    protected static ?string $heading = 'Draft list';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('author')->where('status', Post::STATUS_DRAFT)->latest('updated_at'))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('author.name')->label('Author'),
                TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
