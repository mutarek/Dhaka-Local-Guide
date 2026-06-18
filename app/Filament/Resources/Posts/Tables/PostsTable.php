<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use App\Services\FacebookSharingService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                ImageColumn::make('featured_image')
                    ->disk('public')
                    ->square()
                    ->toggleable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Post::STATUS_PUBLISHED => 'success',
                        Post::STATUS_SCHEDULED => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('is_trending')
                    ->label('Trending')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('seo_score')
                    ->label('SEO')
                    ->state(fn (Post $record): string => $record->seoScore().'%')
                    ->badge()
                    ->color(fn (Post $record): string => $record->seoScore() >= 80 ? 'success' : ($record->seoScore() >= 50 ? 'warning' : 'danger')),
                TextColumn::make('facebook_share_status')
                    ->label('Facebook')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Post::FACEBOOK_SHARED => 'success',
                        Post::FACEBOOK_FAILED => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Post::STATUS_DRAFT => 'Draft',
                        Post::STATUS_PUBLISHED => 'Published',
                        Post::STATUS_SCHEDULED => 'Scheduled',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Filter::make('published')
                    ->query(fn (Builder $query): Builder => $query->published())
                    ->label('Currently public'),
                TernaryFilter::make('is_featured')
                    ->label('Featured'),
                TernaryFilter::make('is_trending')
                    ->label('Trending'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('shareToFacebook')
                    ->label('Share to Facebook')
                    ->icon('heroicon-o-share')
                    ->visible(fn (Post $record): bool => $record->status === Post::STATUS_PUBLISHED)
                    ->requiresConfirmation()
                    ->action(fn (Post $record) => app(FacebookSharingService::class)->share($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => Post::STATUS_PUBLISHED, 'published_at' => now()])),
                    BulkAction::make('unpublish')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => Post::STATUS_DRAFT])),
                    BulkAction::make('updateCategory')
                        ->label('Update category')
                        ->form([
                            Select::make('category_id')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(fn (Collection $records, array $data) => $records->each->update(['category_id' => $data['category_id']])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
