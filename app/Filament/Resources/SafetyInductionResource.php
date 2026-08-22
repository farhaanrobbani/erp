<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SafetyInductionResource\Pages;
use App\Models\Employee;
use App\Models\SafetyInduction;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SafetyInductionResource extends Resource
{
    protected static ?string $model = SafetyInduction::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'HSE';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Induksi Keselamatan';

    protected static ?string $modelLabel = 'Induksi Keselamatan';

    protected static ?string $pluralModelLabel = 'Induksi Keselamatan';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_safety-induction') ?? false;
    }

    public static function employeeLabel($record): string
    {
        return $record->employee_no . ' — ' . $record->user->name;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Induksi')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => static::employeeLabel($record))
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('induction_date')
                            ->label('Tanggal Induksi')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('trainer')
                            ->label('Trainer / Pemateri')
                            ->maxLength(150),
                        Forms\Components\TextInput::make('topic')
                            ->label('Topik')
                            ->maxLength(200),
                        Forms\Components\Select::make('result')
                            ->label('Hasil')
                            ->options([
                                'pass' => 'Lulus',
                                'fail' => 'Tidak Lulus',
                            ])
                            ->default('pass')
                            ->required()
                            ->native(false),
                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('Masa Berlaku Sampai'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.employee_no')
                    ->label('NIP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Karyawan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('topic')
                    ->label('Topik')
                    ->limit(30)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('trainer')
                    ->label('Trainer')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('induction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('result')
                    ->label('Hasil')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'pass' ? 'Lulus' : 'Gagal')
                    ->color(fn (string $state): string => $state === 'pass' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Berlaku Sampai')
                    ->date('d M Y')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state < now() => 'danger',
                        $state < now()->addDays(30) => 'warning',
                        default => 'success',
                    })
                    ->placeholder('-'),
            ])
            ->defaultSort('induction_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->label('Hasil')
                    ->options(['pass' => 'Lulus', 'fail' => 'Tidak Lulus']),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'employee_no')
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
            'index' => Pages\ListSafetyInductions::route('/'),
            'create' => Pages\CreateSafetyInduction::route('/create'),
            'view' => Pages\ViewSafetyInduction::route('/{record}'),
            'edit' => Pages\EditSafetyInduction::route('/{record}/edit'),
        ];
    }
}
