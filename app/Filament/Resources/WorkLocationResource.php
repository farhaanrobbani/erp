<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkLocationResource\Pages;
use App\Models\WorkLocation;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkLocationResource extends Resource
{
    protected static ?string $model = WorkLocation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'HRD';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Lokasi Kerja';

    protected static ?string $modelLabel = 'Lokasi Kerja';

    protected static ?string $pluralModelLabel = 'Lokasi Kerja';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_work-location') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lokasi')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->rows(2)
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),
                        Forms\Components\TextInput::make('radius_m')
                            ->label('Radius (meter)')
                            ->numeric()
                            ->minValue(0)
                            ->default(100),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lokasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->limit(40)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Latitude')
                    ->numeric(6)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Longitude')
                    ->numeric(6)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('radius_m')
                    ->label('Radius')
                    ->suffix(' m')
                    ->placeholder('-'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('name')
            ->actions([
                Actions\ViewAction::make(),
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
            'index' => Pages\ListWorkLocations::route('/'),
            'create' => Pages\CreateWorkLocation::route('/create'),
            'view' => Pages\ViewWorkLocation::route('/{record}'),
            'edit' => Pages\EditWorkLocation::route('/{record}/edit'),
        ];
    }
}
