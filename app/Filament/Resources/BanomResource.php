<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BanomResource\Pages;
use App\Models\Banom;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class BanomResource extends Resource
{
    protected static ?string $model = Banom::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Badan Otonom';

    protected static UnitEnum|string|null $navigationGroup = 'Organisasi';
    
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Informasi Banom')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Banom')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo (PNG tanpa background)')
                            ->image()
                            ->acceptedFileTypes(['image/png'])
                            ->directory('banoms/logos')
                            ->maxSize(1024)
                            ->imageEditor()
                            ->helperText('Upload logo dalam format PNG dengan background transparan'),
                        
                        Forms\Components\FileUpload::make('banner_image')
                            ->label('Banner')
                            ->image()
                            ->directory('banoms/banners')
                            ->maxSize(2048)
                            ->imageEditor(),
                        
                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->placeholder('https://example.com'),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email(),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->tel(),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Deskripsi & Alamat')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Pengurus Banom')
                    ->schema([
                        Forms\Components\Repeater::make('management')
                            ->relationship('management')
                            ->label('Daftar Pengurus')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('position')
                                    ->label('Jabatan')
                                    ->required()
                                    ->placeholder('Contoh: Ketua, Sekretaris, Bendahara')
                                    ->maxLength(255),
                                
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto')
                                    ->image()
                                    ->directory('banoms/management')
                                    ->maxSize(1024)
                                    ->imageEditor()
                                    ->avatar()
                                    ->columnSpanFull(),
                                
                                Forms\Components\TextInput::make('period')
                                    ->label('Periode')
                                    ->placeholder('Contoh: 2023-2028')
                                    ->maxLength(50),
                                
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Pengurus')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->collapsed()
                            ->columnSpanFull(),
                    ])
                    ->description('Tambahkan pengurus seperti Ketua, Sekretaris, Bendahara, dan lainnya'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->size(50),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('website')
                    ->label('Website')
                    ->url(fn ($record) => $record->website)
                    ->openUrlInNewTab()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
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
            'index' => Pages\ListBanoms::route('/'),
            'create' => Pages\CreateBanom::route('/create'),
            'edit' => Pages\EditBanom::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
