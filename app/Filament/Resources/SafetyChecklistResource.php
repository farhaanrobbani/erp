<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SafetyChecklistResource\Pages;
use App\Models\SafetyChecklist;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SafetyChecklistResource extends Resource
{
    protected static ?string $model = SafetyChecklist::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'HSE';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Checklist Keselamatan';

    protected static ?string $modelLabel = 'Checklist';

    protected static ?string $pluralModelLabel = 'Checklist Keselamatan';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_safety-checklist') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Hasil Pemeriksaan')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('check_date')
                            ->label('Tanggal Pemeriksaan')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('item_name')
                            ->label('Nama Item / Peralatan')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Kondisi')
                            ->options([
                                'ok' => 'OK (Layak)',
                                'needs_repair' => 'Perlu Perbaikan',
                                'not_available' => 'Tidak Tersedia',
                            ])
                            ->default('ok')
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('note')
                            ->label('Catatan')
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
                Tables\Columns\TextColumn::make('check_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('item_name')
                    ->label('Item')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Kondisi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ok' => 'OK',
                        'needs_repair' => 'Perlu Perbaikan',
                        'not_available' => 'Tidak Tersedia',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'ok' => 'success',
                        'needs_repair' => 'warning',
                        'not_available' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('checker.name')
                    ->label('Diperiksa Oleh')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(30)
                    ->placeholder('-'),
            ])
            ->defaultSort('check_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Kondisi')
                    ->options([
                        'ok' => 'OK',
                        'needs_repair' => 'Perlu Perbaikan',
                        'not_available' => 'Tidak Tersedia',
                    ]),
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSafetyChecklists::route('/'),
            'create' => Pages\CreateSafetyChecklist::route('/create'),
            'view' => Pages\ViewSafetyChecklist::route('/{record}'),
            'edit' => Pages\EditSafetyChecklist::route('/{record}/edit'),
        ];
    }
}
