<?php

namespace App\Filament\Resources;

use App\Enums\CareerApplicationStatus;
use App\Filament\Resources\CareerApplicationResource\Pages;
use App\Models\CareerApplication;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class CareerApplicationResource extends Resource
{
    protected static ?string $model = CareerApplication::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_career-application') ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = CareerApplication::where('status', CareerApplicationStatus::New->value)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Status Lamaran')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Proses')
                            ->options(fn () => collect(CareerApplicationStatus::cases())->mapWithKeys(
                                fn (CareerApplicationStatus $s) => [$s->value => $s->label()]
                            )->all())
                            ->required()
                            ->helperText('Perbarui status untuk menandai lamaran diproses'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobVacancy.title')
                    ->label('Posisi yang Dilamar')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. HP')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->limit(25)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (CareerApplicationStatus $state) => $state->label())
                    ->color(fn (CareerApplicationStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dilamar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(CareerApplicationStatus::cases())->mapWithKeys(
                        fn (CareerApplicationStatus $s) => [$s->value => $s->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('job_vacancy_id')
                    ->label('Lowongan')
                    ->relationship('jobVacancy', 'title'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('download_resume')
                    ->label('Unduh CV')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (CareerApplication $record) => filled($record->resume_path))
                    ->url(fn (CareerApplication $record) => Storage::disk('public')->url($record->resume_path))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListCareerApplications::route('/'),
            'view' => Pages\ViewCareerApplication::route('/{record}'),
            'edit' => Pages\EditCareerApplication::route('/{record}/edit'),
        ];
    }
}
