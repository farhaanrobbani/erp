<?php

namespace App\Filament\Resources;

use App\Enums\JobVacancyStatus;
use App\Enums\JobVacancyType;
use App\Filament\Resources\JobVacancyResource\Pages;
use App\Models\JobVacancy;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JobVacancyResource extends Resource
{
    protected static ?string $model = JobVacancy::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_job-vacancy') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Lowongan')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Posisi')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(220)
                            ->helperText('Otomatis terisi dari judul'),
                        Forms\Components\Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('type')
                            ->label('Jenis')
                            ->options(fn () => collect(JobVacancyType::cases())->mapWithKeys(
                                fn (JobVacancyType $t) => [$t->value => $t->label()]
                            )->all())
                            ->default(JobVacancyType::Fulltime->value)
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(JobVacancyStatus::cases())->mapWithKeys(
                                fn (JobVacancyStatus $s) => [$s->value => $s->label()]
                            )->all())
                            ->default(JobVacancyStatus::Open->value)
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(150),
                        Forms\Components\DatePicker::make('deadline')
                            ->label('Batas Akhir Lamaran')
                            ->nullable(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Pekerjaan')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('requirements')
                            ->label('Persyaratan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Posisi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departemen')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (JobVacancyType $state) => $state->label())
                    ->color(fn (JobVacancyType $state) => 'info'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (JobVacancyStatus $state) => $state->label())
                    ->color(fn (JobVacancyStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Pelamar')
                    ->counts('applications')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Batas Akhir')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(JobVacancyStatus::cases())->mapWithKeys(
                        fn (JobVacancyStatus $s) => [$s->value => $s->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name'),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('applications');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobVacancies::route('/'),
            'create' => Pages\CreateJobVacancy::route('/create'),
            'view' => Pages\ViewJobVacancy::route('/{record}'),
            'edit' => Pages\EditJobVacancy::route('/{record}/edit'),
        ];
    }
}
