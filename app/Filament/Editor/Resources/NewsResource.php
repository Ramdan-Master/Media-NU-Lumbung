<?php

namespace App\Filament\Editor\Resources;

use App\Filament\Editor\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Berita')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Berita')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->maxLength(500)
                            ->rows(3),

                        Forms\Components\RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('news')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('tags')
                            ->label('Tag')
                            ->placeholder('Tambah tag...')
                            ->helperText('Tekan Enter untuk menambah tag'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Terbit',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit')
                            ->default(now())
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Berita Utama (Global)')
                            ->helperText('Jika dicentang, berita ini akan tampil sebagai berita utama di halaman utama')
                            ->visible(fn () => !auth()->user()->isEditor() || !auth()->user()->area_id),

                        Forms\Components\Toggle::make('is_trending')
                            ->label('Trending (Global)')
                            ->helperText('Jika dicentang, berita ini akan tampil sebagai trending di halaman utama')
                            ->visible(fn () => !auth()->user()->isEditor() || !auth()->user()->area_id),

                        Forms\Components\Toggle::make('is_featured_in_area')
                            ->label('Berita Utama di Area')
                            ->helperText('Jika dicentang, berita ini akan tampil sebagai berita utama di halaman area Anda')
                            ->default(fn ($record) => $record ? in_array(auth()->user()->area_id, $record->featured_areas ?? []) : false)
                            ->afterStateUpdated(function ($state, Forms\Set $set, $record) {
                                if ($record && auth()->user()->isEditor() && auth()->user()->area_id) {
                                    $featuredAreas = $record->featured_areas ?? [];
                                    if ($state) {
                                        if (!in_array(auth()->user()->area_id, $featuredAreas)) {
                                            $featuredAreas[] = auth()->user()->area_id;
                                        }
                                    } else {
                                        $featuredAreas = array_diff($featuredAreas, [auth()->user()->area_id]);
                                    }
                                    $set('featured_areas', $featuredAreas);
                                }
                            }),

                        Forms\Components\Toggle::make('is_trending_in_area')
                            ->label('Trending di Area')
                            ->helperText('Jika dicentang, berita ini akan tampil sebagai trending di halaman area Anda')
                            ->default(fn ($record) => $record ? in_array(auth()->user()->area_id, $record->trending_areas ?? []) : false)
                            ->afterStateUpdated(function ($state, Forms\Set $set, $record) {
                                if ($record && auth()->user()->isEditor() && auth()->user()->area_id) {
                                    $trendingAreas = $record->trending_areas ?? [];
                                    if ($state) {
                                        if (!in_array(auth()->user()->area_id, $trendingAreas)) {
                                            $trendingAreas[] = auth()->user()->area_id;
                                        }
                                    } else {
                                        $trendingAreas = array_diff($trendingAreas, [auth()->user()->area_id]);
                                    }
                                    $set('trending_areas', $trendingAreas);
                                }
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Dilihat')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Terbit',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Berita Utama')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),

                Tables\Filters\Filter::make('is_trending')
                    ->label('Trending')
                    ->query(fn (Builder $query): Builder => $query->where('is_trending', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Restrict editors to their assigned area
        if (auth()->user()->isEditor() && auth()->user()->area_id) {
            $query->where('area_id', auth()->user()->area_id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->isEditor() && auth()->user()->area_id;
    }

    public static function canEdit($record): bool
    {
        if (auth()->user()->isEditor()) {
            return $record->area_id === auth()->user()->area_id;
        }

        return true;
    }

    public static function canDelete($record): bool
    {
        if (auth()->user()->isEditor()) {
            return $record->area_id === auth()->user()->area_id;
        }

        return true;
    }
}
