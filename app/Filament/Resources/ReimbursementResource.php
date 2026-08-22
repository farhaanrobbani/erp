<?php

namespace App\Filament\Resources;

use App\Enums\ReimbursementStatus;
use App\Filament\Resources\ReimbursementResource\Pages;
use App\Models\Reimbursement;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReimbursementResource extends Resource
{
    protected static ?string $model = Reimbursement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Reimbursement';

    protected static ?string $modelLabel = 'Reimbursement';

    protected static ?string $pluralModelLabel = 'Reimbursement';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_reimbursement') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Pengajuan')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Pengajuan')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Rincian Biaya')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->label('')
                            ->schema([
                                Forms\Components\Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'transport' => 'Transport',
                                        'meal' => 'Makan',
                                        'lodging' => 'Penginapan',
                                        'material' => 'Material',
                                        'tool' => 'Peralatan',
                                        'other' => 'Lainnya',
                                    ])
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('amount')
                                    ->label('Jumlah (Rp)')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->minValue(0),
                                Forms\Components\TextInput::make('description')
                                    ->label('Keterangan')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('receipt_path')
                                    ->label('Bon/Struk')
                                    ->disk('public')
                                    ->directory('reimbursement-receipts')
                                    ->image()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible(),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Biaya (Rp)')
                            ->readOnly()
                            ->prefix('Rp')
                            ->dehydrated()
                            ->default(0),
                    ]),
                Section::make('Status & Persetujuan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(ReimbursementStatus::cases())->mapWithKeys(
                                fn (ReimbursementStatus $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(ReimbursementStatus::Pending->value)
                            ->required(),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Waktu Pembayaran')
                            ->seconds(false),
                        Forms\Components\TextInput::make('rejected_reason')
                            ->label('Alasan Penolakan')
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
                Tables\Columns\TextColumn::make('employee.employee_no')
                    ->label('NIP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Karyawan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->alignRight(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (ReimbursementStatus $state) => $state->label())
                    ->color(fn (ReimbursementStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(fn () => collect(ReimbursementStatus::cases())->mapWithKeys(
                        fn (ReimbursementStatus $c) => [$c->value => $c->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'employee_no')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\Action::make('approve_pm')
                    ->label('Setujui PM')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Reimbursement $record): bool => $record->status === ReimbursementStatus::Pending && auth()->user()->hasAnyRole(['project_manager', 'super_admin']))
                    ->action(fn (Reimbursement $record) => $record->update([
                        'status' => ReimbursementStatus::PmApproved,
                        'approved_by_pm' => auth()->id(),
                    ])),
                Actions\Action::make('approve_finance')
                    ->label('Setujui Finance')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Reimbursement $record): bool => $record->status === ReimbursementStatus::PmApproved && auth()->user()->hasAnyRole(['finance', 'super_admin']))
                    ->action(fn (Reimbursement $record) => $record->update([
                        'status' => ReimbursementStatus::FinanceApproved,
                        'approved_by_finance' => auth()->id(),
                    ])),
                Actions\Action::make('approve_director')
                    ->label('Setujui Direktur')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Reimbursement $record): bool => $record->status === ReimbursementStatus::FinanceApproved && auth()->user()->hasAnyRole(['super_admin']))
                    ->action(fn (Reimbursement $record) => $record->update([
                        'status' => ReimbursementStatus::DirectorApproved,
                        'approved_by_director' => auth()->id(),
                    ])),
                Actions\Action::make('mark_paid')
                    ->label('Bayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Reimbursement $record): bool => $record->status === ReimbursementStatus::DirectorApproved && auth()->user()->hasAnyRole(['finance', 'super_admin']))
                    ->action(fn (Reimbursement $record) => $record->update([
                        'status' => ReimbursementStatus::Paid,
                        'paid_at' => now(),
                    ])),
                Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Reimbursement $record): bool => in_array($record->status, [ReimbursementStatus::Pending, ReimbursementStatus::PmApproved, ReimbursementStatus::FinanceApproved]) && auth()->user()->can('update_reimbursement'))
                    ->action(fn (Reimbursement $record) => $record->update([
                        'status' => ReimbursementStatus::Rejected,
                        'rejected_reason' => 'Ditolak',
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
            'index' => Pages\ListReimbursements::route('/'),
            'create' => Pages\CreateReimbursement::route('/create'),
            'view' => Pages\ViewReimbursement::route('/{record}'),
            'edit' => Pages\EditReimbursement::route('/{record}/edit'),
        ];
    }
}
