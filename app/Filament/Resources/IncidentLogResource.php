<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentLogResource\Pages;
use App\Models\IncidentLog;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncidentLogResource extends Resource
{
    protected static ?string $model = IncidentLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|\UnitEnum|null $navigationGroup = 'HSE';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Log Insiden';

    protected static ?string $modelLabel = 'Insiden';

    protected static ?string $pluralModelLabel = 'Log Insiden';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_incident-log') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Detail Insiden')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('incident_date')
                            ->label('Tanggal Kejadian')
                            ->required()
                            ->default(now()),
                        Forms\Components\TimePicker::make('incident_time')
                            ->label('Jam Kejadian')
                            ->seconds(false),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(200),
                        Forms\Components\Select::make('incident_type')
                            ->label('Jenis Insiden')
                            ->options([
                                'near_miss' => 'Near Miss (Hampir Terjadi)',
                                'first_aid' => 'Pertolongan Pertama',
                                'lost_time_injury' => 'Hilang Hari Kerja',
                                'fatality' => 'Fatal',
                                'property_damage' => 'Kerusakan Properti',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('severity')
                            ->label('Tingkat Keparahan')
                            ->options([
                                'low' => 'Rendah',
                                'medium' => 'Sedang',
                                'high' => 'Tinggi',
                                'critical' => 'Kritis',
                            ])
                            ->default('medium')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Kejadian')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Investigasi & Tindak Lanjut')
                    ->schema([
                        Forms\Components\Textarea::make('root_cause')
                            ->label('Akar Masalah')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('corrective_action')
                            ->label('Tindakan Korektif')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'open' => 'Terbuka',
                                'investigating' => 'Sedang Ditangani',
                                'closed' => 'Ditutup',
                            ])
                            ->default('open')
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('incident_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(25)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('incident_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'near_miss' => 'Near Miss',
                        'first_aid' => 'P3K',
                        'lost_time_injury' => 'Hilang Hari Kerja',
                        'fatality' => 'Fatal',
                        'property_damage' => 'Kerusakan Properti',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'near_miss' => 'info',
                        'first_aid' => 'warning',
                        'lost_time_injury' => 'danger',
                        'fatality' => 'danger',
                        'property_damage' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('severity')
                    ->label('Keparahan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                        'critical' => 'Kritis',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Terbuka',
                        'investigating' => 'Ditangani',
                        'closed' => 'Ditutup',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'investigating' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('incident_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Terbuka',
                        'investigating' => 'Sedang Ditangani',
                        'closed' => 'Ditutup',
                    ]),
                Tables\Filters\SelectFilter::make('severity')
                    ->label('Keparahan')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                        'critical' => 'Kritis',
                    ]),
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\Action::make('close')
                    ->label('Tutup')
                    ->icon('heroicon-o-lock-closed')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Tandai insiden ini sebagai selesai ditangani?')
                    ->visible(fn (IncidentLog $record): bool => $record->status !== 'closed' && auth()->user()->can('update_incident-log'))
                    ->action(fn (IncidentLog $record) => $record->update(['status' => 'closed'])),
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
            'index' => Pages\ListIncidentLogs::route('/'),
            'create' => Pages\CreateIncidentLog::route('/create'),
            'view' => Pages\ViewIncidentLog::route('/{record}'),
            'edit' => Pages\EditIncidentLog::route('/{record}/edit'),
        ];
    }
}
