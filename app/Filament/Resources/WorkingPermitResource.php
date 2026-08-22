<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkingPermitResource\Pages;
use App\Models\WorkingPermit;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkingPermitResource extends Resource
{
    protected static ?string $model = WorkingPermit::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Tender';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Perizinan & Sertifikat';

    protected static ?string $modelLabel = 'Perizinan/Sertifikat';

    protected static ?string $pluralModelLabel = 'Perizinan & Sertifikat';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_working-permit') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Permit')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'sbu' => 'SBU',
                                'skk' => 'SKK',
                                'ska' => 'SKA',
                                'k3_umum' => 'K3 Umum',
                                'other' => 'Lainnya',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('holder_type')
                            ->label('Pemegang')
                            ->options([
                                'company' => 'Perusahaan',
                                'person' => 'Personal (Karyawan)',
                            ])
                            ->default('company')
                            ->required()
                            ->live()
                            ->native(false),
                        Forms\Components\Select::make('holder_employee_id')
                            ->label('Karyawan Pemegang')
                            ->relationship('holderEmployee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->visible(fn (Get $get): bool => $get('holder_type') === 'person')
                            ->required(fn (Get $get): bool => $get('holder_type') === 'person'),
                        Forms\Components\TextInput::make('number')
                            ->label('Nomor')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('issuer')
                            ->label('Penerbit')
                            ->maxLength(150),
                    ])
                    ->columns(2),
                Section::make('Masa Berlaku & Dokumen')
                    ->schema([
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Tanggal Terbit')
                            ->required(),
                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('Kedaluwarsa')
                            ->required(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File')
                            ->disk('public')
                            ->directory('working-permits'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sbu' => 'SBU',
                        'skk' => 'SKK',
                        'ska' => 'SKA',
                        'k3_umum' => 'K3 Umum',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('holder_display')
                    ->label('Pemegang')
                    ->state(function (WorkingPermit $record): string {
                        if ($record->holder_type === 'person') {
                            return $record->holderEmployee?->user->name ?? '-';
                        }

                        return 'Perusahaan';
                    }),
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issuer')
                    ->label('Penerbit')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Kedaluwarsa')
                    ->date('d M Y')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state < now() => 'danger',
                        $state < now()->addDays(30) => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
            ])
            ->defaultSort('expiry_date')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'sbu' => 'SBU',
                        'skk' => 'SKK',
                        'ska' => 'SKA',
                        'k3_umum' => 'K3 Umum',
                        'other' => 'Lainnya',
                    ]),
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
            'index' => Pages\ListWorkingPermits::route('/'),
            'create' => Pages\CreateWorkingPermit::route('/create'),
            'view' => Pages\ViewWorkingPermit::route('/{record}'),
            'edit' => Pages\EditWorkingPermit::route('/{record}/edit'),
        ];
    }
}
