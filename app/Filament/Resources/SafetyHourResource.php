<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SafetyHourResource\Pages;
use App\Models\SafetyHour;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SafetyHourResource extends Resource
{
    protected static ?string $model = SafetyHour::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|\UnitEnum|null $navigationGroup = 'HSE';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Jam Kerja Selamat';

    protected static ?string $modelLabel = 'Jam Kerja Selamat';

    protected static ?string $pluralModelLabel = 'Jam Kerja Selamat';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_safety-hour') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Rekap Periode')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),
                        Forms\Components\DatePicker::make('period')
                            ->label('Periode (pilih tanggal pada bulan terkait)')
                            ->required()
                            ->displayFormat('M Y')
                            ->rules([
                                fn (Get $get, ?string $operation, $record): \Closure => function (string $attribute, $value, \Closure $fail) use ($get, $operation, $record) {
                                    $projectId = $get('project_id');
                                    if (!$projectId || !$value) {
                                        return;
                                    }

                                    $query = SafetyHour::where('project_id', $projectId)
                                        ->whereYear('period', substr($value, 0, 4))
                                        ->whereMonth('period', substr($value, 5, 2));

                                    if ($operation === 'edit' && $record) {
                                        $query->where('id', '!=', $record->id);
                                    }

                                    if ($query->exists()) {
                                        $fail('Proyek ini sudah memiliki rekap untuk periode tersebut.');
                                    }
                                },
                            ]),
                        Forms\Components\TextInput::make('total_work_hours')
                            ->label('Total Jam Kerja')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->prefix('Jam'),
                        Forms\Components\TextInput::make('man_days')
                            ->label('Orang-Hari (Man Days)')
                            ->numeric()
                            ->integer()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\TextInput::make('zero_accident_days')
                            ->label('Hari Tanpa Kecelakaan')
                            ->numeric()
                            ->integer()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->date('M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_work_hours')
                    ->label('Total Jam Kerja')
                    ->numeric(0)
                    ->alignRight(),
                Tables\Columns\TextColumn::make('man_days')
                    ->label('Orang-Hari')
                    ->numeric(0)
                    ->alignRight(),
                Tables\Columns\TextColumn::make('zero_accident_days')
                    ->label('Hari Zero Accident')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('recorder.name')
                    ->label('Dicatat Oleh')
                    ->placeholder('-'),
            ])
            ->defaultSort('period', 'desc')
            ->filters([
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
            'index' => Pages\ListSafetyHours::route('/'),
            'create' => Pages\CreateSafetyHour::route('/create'),
            'view' => Pages\ViewSafetyHour::route('/{record}'),
            'edit' => Pages\EditSafetyHour::route('/{record}/edit'),
        ];
    }
}
