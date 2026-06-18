<?php

namespace App\Filament\Resources\Advertisements\Schemas;

use App\Models\AdPackage;
use App\Models\Advertisement;
use App\Support\SecureUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class AdvertisementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Campaign')
                    ->columns(2)
                    ->schema([
                        Select::make('advertiser_id')
                            ->label('Advertiser')
                            ->relationship('advertiser', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('ad_package_id')
                            ->label('Package')
                            ->relationship('adPackage', 'name', modifyQueryUsing: fn ($query) => $query->where('status', AdPackage::STATUS_ACTIVE))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set): void {
                                $package = $state ? AdPackage::query()->find($state) : null;

                                if ($package) {
                                    $set('placement_position', $package->placement_type);

                                    if ($get('start_date')) {
                                        $set('end_date', Carbon::parse($get('start_date'))->addDays($package->duration_days - 1)->toDateString());
                                    }
                                }
                            })
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Desktop ad image')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('1400')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->disk('public')
                            ->directory('ads')
                            ->getUploadedFileNameForStorageUsing(fn ($file): string => SecureUpload::imageFileName($file))
                            ->visibility('public')
                            ->maxSize(2048)
                            ->required(),
                        FileUpload::make('mobile_image')
                            ->label('Mobile ad image')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('900')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->disk('public')
                            ->directory('ads/mobile')
                            ->getUploadedFileNameForStorageUsing(fn ($file): string => SecureUpload::imageFileName($file))
                            ->visibility('public')
                            ->maxSize(1536),
                        TextInput::make('destination_url')
                            ->url()
                            ->required()
                            ->maxLength(2048)
                            ->columnSpanFull(),
                    ]),
                Section::make('Placement and targeting')
                    ->columns(2)
                    ->schema([
                        Select::make('placement_position')
                            ->required()
                            ->options(AdPackage::placementOptions())
                            ->searchable(),
                        Select::make('target_type')
                            ->required()
                            ->options(Advertisement::targetOptions())
                            ->default(Advertisement::TARGET_ALL_POSTS)
                            ->live(),
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get): bool => $get('target_type') === Advertisement::TARGET_CATEGORY)
                            ->visible(fn (Get $get): bool => $get('target_type') === Advertisement::TARGET_CATEGORY),
                        Select::make('posts')
                            ->label('Specific posts')
                            ->relationship('posts', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required(fn (Get $get): bool => $get('target_type') === Advertisement::TARGET_SPECIFIC_POSTS)
                            ->visible(fn (Get $get): bool => $get('target_type') === Advertisement::TARGET_SPECIFIC_POSTS),
                        DatePicker::make('start_date')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set): void {
                                $package = $get('ad_package_id') ? AdPackage::query()->find($get('ad_package_id')) : null;

                                if ($state && $package) {
                                    $set('end_date', Carbon::parse($state)->addDays($package->duration_days - 1)->toDateString());
                                }
                            }),
                        DatePicker::make('end_date')
                            ->required()
                            ->after('start_date')
                            ->helperText('Auto-calculated from package duration, but can be adjusted.'),
                        Select::make('status')
                            ->required()
                            ->options([
                                Advertisement::STATUS_DRAFT => 'Draft',
                                Advertisement::STATUS_ACTIVE => 'Active',
                                Advertisement::STATUS_EXPIRED => 'Expired',
                                Advertisement::STATUS_PAUSED => 'Paused',
                            ])
                            ->default(Advertisement::STATUS_DRAFT),
                        TextInput::make('priority')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Higher priority ads are selected first.'),
                    ]),
                Section::make('Payment and SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount_paid')
                            ->required()
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0)
                            ->default(0),
                        Select::make('payment_status')
                            ->required()
                            ->options([
                                Advertisement::PAYMENT_UNPAID => 'Unpaid',
                                Advertisement::PAYMENT_PARTIAL => 'Partial',
                                Advertisement::PAYMENT_PAID => 'Paid',
                            ])
                            ->default(Advertisement::PAYMENT_UNPAID),
                        Toggle::make('sponsored_label')
                            ->label('Show Sponsored label')
                            ->default(true),
                        Toggle::make('open_in_new_tab')
                            ->label('Open in new tab')
                            ->default(true),
                        Toggle::make('nofollow')
                            ->label('Add nofollow')
                            ->default(true),
                    ]),
            ]);
    }
}
