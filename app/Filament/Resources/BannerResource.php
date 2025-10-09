<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Banner';

    protected static UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Informasi Banner')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Banner')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Banner')
                            ->maxLength(1000)
                            ->rows(3),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Banner')
                            ->image()
                            ->directory('banners')
                            ->required()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('link')
                            ->label('Link URL')
                            ->url()
                            ->placeholder('https://example.com')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state && filter_var($state, FILTER_VALIDATE_URL)) {
                                    try {
                                        $response = \Illuminate\Support\Facades\Http::timeout(10)->get($state);
                                        if ($response->successful()) {
                                            $html = $response->body();

                                            // Extract title
                                            preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $titleMatches);
                                            $title = $titleMatches[1] ?? '';

                                            // Extract meta description
                                            preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']*)["\'][^>]*>/i', $html, $descMatches);
                                            $description = $descMatches[1] ?? '';

                                            if ($title) {
                                                $set('title', trim(strip_tags($title)));
                                            }
                                            if ($description) {
                                                $set('description', trim(strip_tags($description)));
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        // Ignore errors
                                    }
                                }
                            }),

                        Forms\Components\Select::make('position')
                            ->label('Posisi')
                            ->options([
                                'home_top' => 'Home - Top',
                                'home_middle' => 'Home - Middle',
                                'home_bottom' => 'Home - Bottom',
                                'sidebar_top' => 'Sidebar - Top',
                                'sidebar_middle' => 'Sidebar - Middle',
                                'area_top' => 'Area - Top',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('area_id')
                            ->label('Daerah')
                            ->relationship('area', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih daerah (opsional)')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Daerah')
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(),
                            ]),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Jadwal Tayang')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->native(false),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->size(80),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Posisi')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('area.name')
                    ->label('Daerah')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Semua daerah')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('position')
                    ->label('Posisi')
                    ->options([
                        'home_top' => 'Home - Top',
                        'home_middle' => 'Home - Middle',
                        'home_bottom' => 'Home - Bottom',
                        'sidebar_top' => 'Sidebar - Top',
                        'sidebar_middle' => 'Sidebar - Middle',
                        'area_top' => 'Area - Top',
                    ]),

                Tables\Filters\SelectFilter::make('area')
                    ->label('Daerah')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
