<?php

namespace App\Filament\Resources;

use App\Filament\Resources\McuResource\Pages;
use App\Models\Mcu;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class McuResource extends Resource
{
    protected static ?string $model = Mcu::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|\UnitEnum|null $navigationGroup = 'HSE';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'MCU Karyawan';

    protected static ?string $modelLabel = 'MCU';

    protected static ?string $pluralModelLabel = 'MCU Karyawan';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_mcu') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data MCU')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('mcu_date')
                            ->label('Tanggal MCU')
                            ->required()
                            ->default(now()),
                        Forms\Components\TextInput::make('provider')
                            ->label('Penyedia / Klinik')
                            ->maxLength(150),
                        Forms\Components\Select::make('result')
                            ->label('Hasil')
                            ->options([
                                'fit' => 'Fit (Sehat)',
                                'unfit' => 'Unfit (Tidak Sehat)',
                                'fit_condition' => 'Fit dengan Catatan',
                            ])
                            ->native(false),
                        Forms\Components\DatePicker::make('next_mcu_date')
                            ->label('Jadwal MCU Berikutnya'),
                    ])
                    ->columns(2),
                Section::make('Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Hasil MCU')
                            ->disk('public')
                            ->directory('mcu-documents')
                            ->columnSpanFull(),
                    ]),
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
                Tables\Columns\TextColumn::make('mcu_date')
                    ->label('Tanggal MCU')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->label('Penyedia')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('result')
                    ->label('Hasil')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'fit' => 'Fit',
                        'unfit' => 'Unfit',
                        'fit_condition' => 'Fit + Catatan',
                        default => '-',
                    })
                    ->color(fn ($state): string => match ($state) {
                        'fit' => 'success',
                        'unfit' => 'danger',
                        'fit_condition' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('next_mcu_date')
                    ->label('MCU Berikutnya')
                    ->date('d M Y')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state < now() => 'danger',
                        $state < now()->addDays(30) => 'warning',
                        default => 'info',
                    })
                    ->placeholder('-'),
            ])
            ->defaultSort('mcu_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->label('Hasil')
                    ->options([
                        'fit' => 'Fit',
                        'unfit' => 'Unfit',
                        'fit_condition' => 'Fit dengan Catatan',
                    ]),
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
            'index' => Pages\ListMcus::route('/'),
            'create' => Pages\CreateMcu::route('/create'),
            'view' => Pages\ViewMcu::route('/{record}'),
            'edit' => Pages\EditMcu::route('/{record}/edit'),
        ];
    }
}
