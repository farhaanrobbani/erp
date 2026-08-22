<?php

namespace App\Filament\Resources;

use App\Enums\ApprovalStatus;
use App\Enums\LeaveType;
use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'HRD';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Cuti';

    protected static ?string $modelLabel = 'Cuti';

    protected static ?string $pluralModelLabel = 'Cuti';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_leave') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Pengajuan Cuti')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('leave_type')
                            ->label('Jenis Cuti')
                            ->options(fn () => collect(LeaveType::cases())->mapWithKeys(
                                fn (LeaveType $c) => [$c->value => $c->label()]
                            )->all())
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateDays($set, $get)),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->afterOrEqual('start_date')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateDays($set, $get)),
                        Forms\Components\TextInput::make('days')
                            ->label('Jumlah Hari')
                            ->numeric()
                            ->minValue(0.5)
                            ->step(0.5)
                            ->helperText('Otomatis dihitung dari rentang tanggal, bisa disesuaikan (mis. setengah hari).')
                            ->required(),
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Dokumen & Persetujuan')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Surat Pendukung')
                            ->disk('public')
                            ->directory('leave-documents'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(ApprovalStatus::cases())->mapWithKeys(
                                fn (ApprovalStatus $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(ApprovalStatus::Pending->value)
                            ->required(),
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Waktu Persetujuan')
                            ->seconds(false)
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function recalculateDays(Set $set, Get $get): void
    {
        $start = $get('start_date');
        $end = $get('end_date');

        if (! $start || ! $end) {
            return;
        }

        $days = \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1;

        $set('days', max(1, (int) $days));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.employee_no')
                    ->label('NIP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Nama Karyawan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leave_type')
                    ->label('Jenis Cuti')
                    ->badge()
                    ->formatStateUsing(fn (LeaveType $state) => $state->label()),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('days')
                    ->label('Hari'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (ApprovalStatus $state) => $state->label())
                    ->color(fn (ApprovalStatus $state) => $state->color()),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('leave_type')
                    ->label('Jenis Cuti')
                    ->options(fn () => collect(LeaveType::cases())->mapWithKeys(
                        fn (LeaveType $c) => [$c->value => $c->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(fn () => collect(ApprovalStatus::cases())->mapWithKeys(
                        fn (ApprovalStatus $c) => [$c->value => $c->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'employee_no')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Leave $record): bool => $record->status === ApprovalStatus::Pending && auth()->user()->can('update_leave'))
                    ->action(function (Leave $record): void {
                        $record->update([
                            'status' => ApprovalStatus::Approved,
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    }),
                Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Leave $record): bool => $record->status === ApprovalStatus::Pending && auth()->user()->can('update_leave'))
                    ->action(function (Leave $record): void {
                        $record->update([
                            'status' => ApprovalStatus::Rejected,
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    }),
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'view' => Pages\ViewLeave::route('/{record}'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
