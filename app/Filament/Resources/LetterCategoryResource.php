<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterCategoryResource\Pages;
use App\Models\LetterCategory;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LetterCategoryResource extends Resource
{
    protected static ?string $model = LetterCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Surat';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_letter-category') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Kode Surat')
                    ->description('Kategori & format penomoran surat keluar')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Surat')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->uppercase()
                            ->helperText('Contoh: MEMO, SPK, KET, SK, TND'),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('code_format')
                            ->label('Format Nomor Surat')
                            ->required()
                            ->default('{NUMBER}/{CODE}/{ROMAN}/{YEAR}')
                            ->helperText('Placeholder: {NUMBER} nomor urut, {CODE} kode surat, {ROMAN} bulan romawi, {YEAR} tahun'),
                        Forms\Components\TextInput::make('pad_length')
                            ->label('Panjang Digit Nomor Urut')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(6)
                            ->helperText('Contoh: 3 → 001, 002, ...'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code_format')
                    ->label('Format Nomor')
                    ->fontFamily('mono'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('requests_count')
                    ->label('Total Pengajuan')
                    ->counts('requests'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLetterCategories::route('/'),
            'create' => Pages\CreateLetterCategory::route('/create'),
            'edit' => Pages\EditLetterCategory::route('/{record}/edit'),
        ];
    }
}
