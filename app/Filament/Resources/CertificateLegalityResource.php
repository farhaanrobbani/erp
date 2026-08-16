<?php

namespace App\Filament\Resources;

use App\Enums\CertificateType;
use App\Filament\Resources\CertificateLegalityResource\Pages;
use App\Models\CertificateLegality;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CertificateLegalityResource extends Resource
{
    protected static ?string $model = CertificateLegality::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_certificate-legality') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Legalitas')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Sertifikat')
                            ->required()
                            ->maxLength(150),
                        Forms\Components\Select::make('type')
                            ->label('Jenis')
                            ->options(fn () => collect(CertificateType::cases())->mapWithKeys(
                                fn (CertificateType $t) => [$t->value => $t->label()]
                            )->all())
                            ->required(),
                        Forms\Components\TextInput::make('number')
                            ->label('Nomor Sertifikat')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('issuer')
                            ->label('Penerbit')
                            ->maxLength(150),
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Tanggal Terbit')
                            ->nullable(),
                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('Berlaku Sampai')
                            ->nullable()
                            ->helperText('Diberi tanda kuning jika mendekati masa berlaku'),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Sertifikat (PDF)')
                            ->disk('public')
                            ->directory('certificates')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif / Tampil di Website')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sertifikat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (CertificateType $state) => $state->label())
                    ->color(fn (CertificateType $state) => 'gray'),
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor')
                    ->fontFamily('mono')
                    ->limit(25),
                Tables\Columns\TextColumn::make('issuer')
                    ->label('Penerbit')
                    ->limit(25)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Berlaku Sampai')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn (CertificateLegality $record) => $record->expiry_date && $record->expiry_date->isPast() ? 'danger' : ($record->expiry_date && $record->expiry_date->lt(now()->addMonths(3)) ? 'warning' : 'gray'))
                    ->formatStateUsing(fn (?string $state, CertificateLegality $record) => $state ? $record->expiry_date->format('d M Y') : '-'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('expiry_date', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(fn () => collect(CertificateType::cases())->mapWithKeys(
                        fn (CertificateType $t) => [$t->value => $t->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status Aktif')
                    ->options([true => 'Aktif', false => 'Nonaktif']),
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
            'index' => Pages\ListCertificateLegalities::route('/'),
            'create' => Pages\CreateCertificateLegality::route('/create'),
            'view' => Pages\ViewCertificateLegality::route('/{record}'),
            'edit' => Pages\EditCertificateLegality::route('/{record}/edit'),
        ];
    }
}
