<?php

namespace App\Filament\Resources;

use App\Enums\TenderStatus;
use App\Filament\Resources\TenderResource\Pages;
use App\Filament\Resources\TenderResource\RelationManagers\DocumentsRelationManager;
use App\Models\Tender;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenderResource extends Resource
{
    protected static ?string $model = Tender::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|\UnitEnum|null $navigationGroup = 'Tender';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Tender';

    protected static ?string $pluralModelLabel = 'Tender';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_tender') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Tender')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Paket')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('client')
                            ->label('Instansi / Klien')
                            ->required()
                            ->maxLength(150),
                        Forms\Components\TextInput::make('source')
                            ->label('Sumber')
                            ->maxLength(255)
                            ->helperText('Contoh: LPSE, e-Katalog, undangan langsung'),
                        Forms\Components\TextInput::make('package_number')
                            ->label('Nomor Paket')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('budget')
                            ->label('Nilai Anggaran (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0),
                        Forms\Components\Select::make('status')
                            ->label('Tahapan')
                            ->options(fn () => collect(TenderStatus::cases())->mapWithKeys(
                                fn (TenderStatus $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(TenderStatus::Announcement->value)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Jadwal & Catatan')
                    ->schema([
                        Forms\Components\DatePicker::make('bid_date')
                            ->label('Tanggal Penawaran'),
                        Forms\Components\DatePicker::make('result_date')
                            ->label('Tanggal Hasil'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Paket')
                    ->limit(45)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client')
                    ->label('Klien')
                    ->searchable(),
                Tables\Columns\TextColumn::make('package_number')
                    ->label('No. Paket')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('budget')
                    ->label('Anggaran')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format((float) $state, 0, ',', '.') : '-')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Tahapan')
                    ->badge()
                    ->formatStateUsing(fn (TenderStatus $state) => $state->label())
                    ->color(fn (TenderStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('bid_date')
                    ->label('Penawaran')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('documents_count')
                    ->label('Dokumen')
                    ->counts('documents'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Tahapan')
                    ->options(fn () => collect(TenderStatus::cases())->mapWithKeys(
                        fn (TenderStatus $c) => [$c->value => $c->label()]
                    )->all()),
            ])
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

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenders::route('/'),
            'create' => Pages\CreateTender::route('/create'),
            'view' => Pages\ViewTender::route('/{record}'),
            'edit' => Pages\EditTender::route('/{record}/edit'),
        ];
    }
}
