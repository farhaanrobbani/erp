<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificationResource\Pages;
use App\Models\Certification;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CertificationResource extends Resource
{
    protected static ?string $model = Certification::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Tender';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Sertifikasi Alat';

    protected static ?string $modelLabel = 'Sertifikasi Alat';

    protected static ?string $pluralModelLabel = 'Sertifikasi Alat';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_certification') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Sertifikasi')
                    ->schema([
                        Forms\Components\TextInput::make('equipment_name')
                            ->label('Nama Peralatan')
                            ->required()
                            ->maxLength(150),
                        Forms\Components\Select::make('certificate_type')
                            ->label('Jenis Sertifikat')
                            ->options([
                                'sertifikasi' => 'Sertifikasi',
                                'kalibrasi' => 'Kalibrasi',
                            ])
                            ->default('sertifikasi')
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('number')
                            ->label('Nomor Sertifikat')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('issuer')
                            ->label('Penerbit')
                            ->required()
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
                            ->label('File Sertifikat')
                            ->disk('public')
                            ->directory('certifications'),
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
                Tables\Columns\TextColumn::make('equipment_name')
                    ->label('Peralatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sertifikasi' => 'Sertifikasi',
                        'kalibrasi' => 'Kalibrasi',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issuer')
                    ->label('Penerbit'),
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
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => basename((string) $state))
                    ->limit(20)
                    ->url(fn ($record): string => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->placeholder('-'),
            ])
            ->defaultSort('expiry_date')
            ->filters([
                Tables\Filters\SelectFilter::make('certificate_type')
                    ->label('Jenis Sertifikat')
                    ->options([
                        'sertifikasi' => 'Sertifikasi',
                        'kalibrasi' => 'Kalibrasi',
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
            'index' => Pages\ListCertifications::route('/'),
            'create' => Pages\CreateCertification::route('/create'),
            'view' => Pages\ViewCertification::route('/{record}'),
            'edit' => Pages\EditCertification::route('/{record}/edit'),
        ];
    }
}
