<?php

namespace App\Filament\Resources;

use App\Enums\PayrollStatus;
use App\Filament\Resources\PayrollResource\Pages;
use App\Models\Payroll;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Payroll';

    protected static ?string $modelLabel = 'Slip Gaji';

    protected static ?string $pluralModelLabel = 'Slip Gaji';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_payroll') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Karyawan & Periode')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('period')
                            ->label('Periode (YYYY-MM)')
                            ->required()
                            ->maxLength(7)
                            ->placeholder('2026-01'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(PayrollStatus::cases())->mapWithKeys(
                                fn (PayrollStatus $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(PayrollStatus::Draft->value)
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Komponen Gaji Pokok')
                    ->schema([
                        Forms\Components\TextInput::make('base_salary')
                            ->label('Gaji Pokok')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('project_allowance')
                            ->label('Tunjangan Proyek')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('transport_allowance')
                            ->label('Tunjangan Transport')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('overtime')
                            ->label('Lembur')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                    ])
                    ->columns(2),
                Section::make('Potongan')
                    ->schema([
                        Forms\Components\TextInput::make('deduction_total')
                            ->label('Potongan')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('tax')
                            ->label('Pajak')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('bpjs_amount')
                            ->label('BPJS')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('net_salary')
                            ->label('Gaji Bersih (Rp)')
                            ->readOnly()
                            ->prefix('Rp')
                            ->dehydrated()
                            ->default(0)
                            ->helperText('Otomatis dihitung: total pemasukan - total potongan.'),
                    ])
                    ->columns(2),
                Section::make('Detail Tambahan')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tipe')
                                    ->options([
                                        'allowance' => 'Tambahan (+)',
                                        'deduction' => 'Potongan (-)',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Komponen')
                                    ->required()
                                    ->maxLength(150)
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('amount')
                                    ->label('Jumlah (Rp)')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp'),
                            ])
                            ->columns(6)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),
                Section::make('Pembayaran')
                    ->schema([
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Waktu Pembayaran')
                            ->seconds(false),
                        Forms\Components\FileUpload::make('slip_path')
                            ->label('Slip Gaji (PDF)')
                            ->disk('public')
                            ->directory('payroll-slips')
                            ->acceptedFileTypes(['application/pdf']),
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
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_salary')
                    ->label('Gaji Bersih')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->alignRight(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (PayrollStatus $state) => $state->label())
                    ->color(fn (PayrollStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Dibayar')
                    ->dateTime('d M Y')
                    ->placeholder('-'),
            ])
            ->defaultSort('period', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(fn () => collect(PayrollStatus::cases())->mapWithKeys(
                        fn (PayrollStatus $c) => [$c->value => $c->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'employee_no')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\Action::make('compute')
                    ->label('Hitung')
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Payroll $record): bool => $record->status === PayrollStatus::Draft && auth()->user()->can('update_payroll'))
                    ->action(function (Payroll $record): void {
                        $income = $record->base_salary + $record->project_allowance + $record->transport_allowance + $record->overtime;
                        $details = $record->details;
                        $additionalAllowance = $details->where('type', 'allowance')->sum('amount');
                        $additionalDeduction = $details->where('type', 'deduction')->sum('amount');
                        $totalDeductions = $record->deduction_total + $record->tax + $record->bpjs_amount + $additionalDeduction;

                        $record->update([
                            'net_salary' => $income + $additionalAllowance - $totalDeductions,
                            'status' => PayrollStatus::Computed,
                        ]);
                    }),
                Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Payroll $record): bool => $record->status === PayrollStatus::Computed && auth()->user()->can('update_payroll'))
                    ->action(fn (Payroll $record) => $record->update(['status' => PayrollStatus::Approved])),
                Actions\Action::make('mark_paid')
                    ->label('Bayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Payroll $record): bool => $record->status === PayrollStatus::Approved && auth()->user()->can('update_payroll'))
                    ->action(fn (Payroll $record) => $record->update([
                        'status' => PayrollStatus::Paid,
                        'paid_at' => now(),
                    ])),
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
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
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'view' => Pages\ViewPayroll::route('/{record}'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
