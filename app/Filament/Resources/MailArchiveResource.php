<?php

namespace App\Filament\Resources;

use App\Enums\MailArchiveType;
use App\Filament\Resources\MailArchiveResource\Pages;
use App\Models\MailArchive;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MailArchiveResource extends Resource
{
    protected static ?string $model = MailArchive::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Surat';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_mail-archive') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Arsip Surat')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Jenis Surat')
                            ->options(fn () => collect(MailArchiveType::cases())->mapWithKeys(
                                fn (MailArchiveType $t) => [$t->value => $t->label()]
                            )->all())
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('letter_number')
                            ->label('Nomor Surat')
                            ->maxLength(255)
                            ->helperText('Kosongkan jika surat masuk tanpa nomor'),
                        Forms\Components\Select::make('letter_category_id')
                            ->label('Kategori')
                            ->relationship('letterCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('letter_request_id')
                            ->label('Tautan Pengajuan (Opsional)')
                            ->relationship('letterRequest', 'generated_letter_number')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('subject')
                            ->label('Perihal')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sender')
                            ->label('Pengirim')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\TextInput::make('recipient')
                            ->label('Penerima')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\DatePicker::make('letter_date')
                            ->label('Tanggal Surat')
                            ->required(),
                        Forms\Components\DatePicker::make('received_date')
                            ->label('Tanggal Diterima')
                            ->nullable(),
                        Forms\Components\Textarea::make('disposition')
                            ->label('Riwayat Disposisi')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Catat disposisi surat masuk: diteruskan ke siapa, instruksi, dst.'),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Berkas Surat (PDF)')
                            ->disk('public')
                            ->directory('mail_archives')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (MailArchiveType $state) => $state->label())
                    ->color(fn (MailArchiveType $state) => $state->color()),
                Tables\Columns\TextColumn::make('letter_number')
                    ->label('Nomor Surat')
                    ->placeholder('-')
                    ->fontFamily('mono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Perihal')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender')
                    ->label('Pengirim')
                    ->limit(25)
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient')
                    ->label('Penerima')
                    ->limit(25),
                Tables\Columns\TextColumn::make('letter_date')
                    ->label('Tanggal Surat')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dispositions_count')
                    ->label('Disposisi')
                    ->counts('dispositions'),
            ])
            ->defaultSort('letter_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(fn () => collect(MailArchiveType::cases())->mapWithKeys(
                        fn (MailArchiveType $t) => [$t->value => $t->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('letter_category_id')
                    ->label('Kategori')
                    ->relationship('letterCategory', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailArchives::route('/'),
            'create' => Pages\CreateMailArchive::route('/create'),
            'view' => Pages\ViewMailArchive::route('/{record}'),
            'edit' => Pages\EditMailArchive::route('/{record}/edit'),
        ];
    }
}
