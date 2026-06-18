<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use App\Models\User;
use App\Support\SecureUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->disabled(fn (): bool => auth()->user()?->role === User::ROLE_AUTHOR)
                            ->dehydrated()
                            ->required(),
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                        Textarea::make('excerpt')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->required()
                            ->options([
                                Post::STATUS_DRAFT => 'Draft',
                                Post::STATUS_PUBLISHED => 'Published',
                                Post::STATUS_SCHEDULED => 'Scheduled',
                            ])
                            ->default(Post::STATUS_DRAFT),
                        DateTimePicker::make('published_at')
                            ->seconds(false)
                            ->helperText('Set this when the post is ready to appear publicly.'),
                        FileUpload::make('featured_image')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('1600')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->disk('public')
                            ->directory('posts')
                            ->getUploadedFileNameForStorageUsing(fn ($file): string => SecureUpload::imageFileName($file))
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        FileUpload::make('gallery_images')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('1400')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('posts/gallery')
                            ->getUploadedFileNameForStorageUsing(fn ($file): string => SecureUpload::imageFileName($file))
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Upload optimized JPG, PNG, or WebP images.')
                            ->columnSpanFull(),
                        TextInput::make('image_alt')
                            ->maxLength(255)
                            ->helperText('Required for strong image SEO.'),
                        TextInput::make('image_caption')
                            ->maxLength(255),
                        Toggle::make('is_featured')
                            ->label('Featured post'),
                        Toggle::make('is_trending')
                            ->label('Trending post'),
                    ]),
                Section::make('SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('meta_title')
                            ->maxLength(255)
                            ->helperText('Recommended length: 50-60 characters.'),
                        TextInput::make('canonical_url')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('focus_keyword')
                            ->maxLength(255)
                            ->helperText('Used by the SEO score indicator.'),
                        Textarea::make('meta_description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Recommended length: 140-160 characters.')
                            ->columnSpanFull(),
                        Repeater::make('faqs')
                            ->label('FAQ schema')
                            ->schema([
                                TextInput::make('question')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('answer')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add FAQ')
                            ->columnSpanFull(),
                        Placeholder::make('seo_score')
                            ->label('SEO score')
                            ->content(fn (?Post $record): string => $record ? $record->seoScore().' / 100' : 'Save the post to calculate the SEO score.'),
                        Placeholder::make('json_ld_preview')
                            ->label('JSON-LD schema preview')
                            ->content(fn (?Post $record): HtmlString => new HtmlString('<pre class="overflow-auto rounded-xl bg-gray-950 p-4 text-xs text-gray-100">'.e(json_encode($record?->schema ?: ['generated' => 'Article, FAQ and Breadcrumb schema are generated on the public page.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)).'</pre>'))
                            ->columnSpanFull(),
                    ]),
                Section::make('Facebook sharing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('auto_share_to_facebook')
                            ->label('Auto-share when published'),
                        Select::make('facebook_share_status')
                            ->options([
                                Post::FACEBOOK_NOT_SHARED => 'Not shared',
                                Post::FACEBOOK_SHARED => 'Shared',
                                Post::FACEBOOK_FAILED => 'Failed',
                            ])
                            ->default(Post::FACEBOOK_NOT_SHARED)
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('facebook_post_id')
                            ->disabled()
                            ->dehydrated(),
                        Textarea::make('facebook_share_error')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
