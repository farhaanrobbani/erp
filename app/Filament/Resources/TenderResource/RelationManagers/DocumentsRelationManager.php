<?php

namespace App\Filament\Resources\TenderResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumen Tender';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Dokumen')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'admin' => 'Administrasi',
                        'legal' => 'Legal',
                        'technical' => 'Teknis',
                        'financial' => 'Keuangan',
                        'other' => 'Lainnya',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\FileUpload::make('file_path')
                    ->label('File')
                    ->disk('public')
                    ->directory('tender-documents')
                    ->required(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrasi',
                        'legal' => 'Legal',
                        'technical' => 'Teknis',
                        'financial' => 'Keuangan',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn (string $state): string => basename($state))
                    ->limit(30)
                    ->url(fn ($record): string => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Diunggah Oleh')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->headerActions([
                Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['uploaded_by'] = auth()->id();

                    return $data;
                }),
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
}
