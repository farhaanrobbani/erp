<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeDocumentResource\Pages;
use App\Models\EmployeeDocument;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeDocumentResource extends Resource
{
    protected static ?string $model = EmployeeDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'HRD';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Dokumen Karyawan';

    protected static ?string $modelLabel = 'Dokumen Karyawan';

    protected static ?string $pluralModelLabel = 'Dokumen Karyawan';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_employee-document') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('doc_type')
                            ->label('Jenis Dokumen')
                            ->options([
                                'ktp' => 'KTP',
                                'npwp' => 'NPWP',
                                'ijazah' => 'Ijazah',
                                'sk' => 'SK Pengangkatan',
                                'bpjs' => 'BPJS',
                                'certificate' => 'Sertifikat',
                                'others' => 'Lainnya',
                            ])
                            ->required(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Dokumen')
                            ->disk('public')
                            ->directory('employee-documents')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Tanggal Terbit'),
                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('Tanggal Kedaluwarsa')
                            ->helperText('Kosongkan jika tidak ada masa berlaku'),
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
                Tables\Columns\TextColumn::make('employee.employee_no')
                    ->label('NIP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Nama Karyawan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ktp' => 'KTP',
                        'npwp' => 'NPWP',
                        'ijazah' => 'Ijazah',
                        'sk' => 'SK',
                        'bpjs' => 'BPJS',
                        'certificate' => 'Sertifikat',
                        'others' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn (string $state): string => basename($state))
                    ->limit(30)
                    ->url(fn (EmployeeDocument $record): string => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Tanggal Terbit')
                    ->date('d M Y')
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
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('doc_type')
                    ->label('Jenis Dokumen')
                    ->options([
                        'ktp' => 'KTP',
                        'npwp' => 'NPWP',
                        'ijazah' => 'Ijazah',
                        'sk' => 'SK',
                        'bpjs' => 'BPJS',
                        'certificate' => 'Sertifikat',
                        'others' => 'Lainnya',
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
            'index' => Pages\ListEmployeeDocuments::route('/'),
            'create' => Pages\CreateEmployeeDocument::route('/create'),
            'view' => Pages\ViewEmployeeDocument::route('/{record}'),
            'edit' => Pages\EditEmployeeDocument::route('/{record}/edit'),
        ];
    }
}
