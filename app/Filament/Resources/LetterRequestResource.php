<?php

namespace App\Filament\Resources;

use App\Enums\LetterRequestStatus;
use App\Filament\Resources\LetterRequestResource\Pages;
use App\Models\LetterRequest;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LetterRequestResource extends Resource
{
    protected static ?string $model = LetterRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Surat';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_letter-request') ?? false;
    }

    /**
     * Scope: employee hanya melihat pengajuan miliknya sendiri,
     * role pengelola surat (hrd/super_admin/finance/pm) melihat semua.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user && ! $user->can('approve_letter-request') && ! $user->hasRole('super_admin')) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Form Pengajuan Nomor Surat')
                    ->description('Isi perihal & tujuan surat, sistem akan menomori otomatis setelah disetujui admin.')
                    ->schema([
                        Forms\Components\Select::make('letter_category_id')
                            ->label('Kode / Kategori Surat')
                            ->relationship('letterCategory', 'name', fn (Builder $query) => $query->where('is_active', true))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('subject', '')),
                        Forms\Components\TextInput::make('subject')
                            ->label('Perihal / Tujuan Surat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('recipient')
                            ->label('Kepada (Instansi/Nama)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('request_date')
                            ->label('Tanggal Surat')
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan / Keterangan')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('status_preview')
                            ->label('Status')
                            ->content(fn (?LetterRequest $record) => $record
                                ? LetterRequestStatus::from($record->status)->label()
                                : 'Pending / Waiting Approval'),
                        Forms\Components\Placeholder::make('generated_number')
                            ->label('Nomor Surat (Setelah Disetujui)')
                            ->content(fn (?LetterRequest $record) => $record?->generated_letter_number ?? '-'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('generated_letter_number')
                    ->label('Nomor Surat')
                    ->placeholder('-')
                    ->fontFamily('mono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('letterCategory.code')
                    ->label('Kode')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Perihal')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengaju')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient')
                    ->label('Tujuan')
                    ->limit(30),
                Tables\Columns\TextColumn::make('request_date')
                    ->label('Tanggal Surat')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (LetterRequestStatus $state) => $state->label())
                    ->color(fn (LetterRequestStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(LetterRequestStatus::cases())->mapWithKeys(
                        fn (LetterRequestStatus $s) => [$s->value => $s->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('letter_category_id')
                    ->label('Kategori')
                    ->relationship('letterCategory', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (LetterRequest $record) => $record->status->value === 'pending'
                        && auth()->user()?->can('update_letter-request')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (LetterRequest $record) => $record->status->value === 'pending'
                        && (auth()->user()?->id === $record->user_id || auth()->user()?->hasRole('super_admin'))),
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
            'index' => Pages\ListLetterRequests::route('/'),
            'create' => Pages\CreateLetterRequest::route('/create'),
            'view' => Pages\ViewLetterRequest::route('/{record}'),
            'edit' => Pages\EditLetterRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        if (! auth()->user()?->can('approve_letter-request')) {
            return null;
        }

        $count = static::getModel()::where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
