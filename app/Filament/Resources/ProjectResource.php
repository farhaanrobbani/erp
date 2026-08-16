<?php

namespace App\Filament\Resources;

use App\Enums\ProjectCategory;
use App\Enums\ProjectClientType;
use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_project') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Proyek')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Proyek')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(220)
                            ->helperText('Otomatis terisi dari nama proyek'),
                        Forms\Components\TextInput::make('client_name')
                            ->label('Nama Klien')
                            ->required()
                            ->maxLength(150),
                        Forms\Components\Select::make('client_type')
                            ->label('Jenis Klien')
                            ->options(fn () => collect(ProjectClientType::cases())->mapWithKeys(
                                fn (ProjectClientType $t) => [$t->value => $t->label()]
                            )->all())
                            ->default(ProjectClientType::Bumn->value)
                            ->required(),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options(fn () => collect(ProjectCategory::cases())->mapWithKeys(
                                fn (ProjectCategory $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(ProjectCategory::Construction->value)
                            ->required(),
                        Forms\Components\TextInput::make('value')
                            ->label('Nilai Kontrak (Rp)')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(ProjectStatus::cases())->mapWithKeys(
                                fn (ProjectStatus $s) => [$s->value => $s->label()]
                            )->all())
                            ->default(ProjectStatus::Ongoing->value)
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(150),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->nullable(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->nullable(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Proyek')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Gambar Cover')
                            ->disk('public')
                            ->directory('projects/covers')
                            ->image()
                            ->imageEditor(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan di Beranda (Unggulan)'),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Dipublikasikan'),
                    ])
                    ->columns(2),
                Section::make('Galeri Proyek')
                    ->schema([
                        Forms\Components\Repeater::make('galleries')
                            ->label('Foto/Video Proyek')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Galeri')
                            ->reorderable()
                            ->collapsible()
                            ->schema([
                                Forms\Components\FileUpload::make('file_path')
                                    ->label('File (Gambar/Video)')
                                    ->disk('public')
                                    ->directory('projects/galleries')
                                    ->image()
                                    ->imageEditor(),
                                Forms\Components\TextInput::make('caption')
                                    ->label('Keterangan')
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Proyek')
                    ->limit(35)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Klien')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_type')
                    ->label('Jenis Klien')
                    ->badge()
                    ->formatStateUsing(fn (ProjectClientType $state) => $state->label())
                    ->color(fn (ProjectClientType $state) => $state->color()),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn (?string $state) => $state ? 'Rp ' . number_format((float) $state, 0, ',', '.') : '-'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus $state) => $state->label())
                    ->color(fn (ProjectStatus $state) => $state->color()),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(ProjectStatus::cases())->mapWithKeys(
                        fn (ProjectStatus $s) => [$s->value => $s->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('client_type')
                    ->label('Jenis Klien')
                    ->options(fn () => collect(ProjectClientType::cases())->mapWithKeys(
                        fn (ProjectClientType $t) => [$t->value => $t->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(fn () => collect(ProjectCategory::cases())->mapWithKeys(
                        fn (ProjectCategory $c) => [$c->value => $c->label()]
                    )->all()),
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
        return parent::getEloquentQuery()->withCount('galleries');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
