<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HseReportResource\Pages;
use App\Models\HseReport;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HseReportResource extends Resource
{
    protected static ?string $model = HseReport::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup = 'HSE';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Laporan HSE';

    protected static ?string $modelLabel = 'Laporan HSE';

    protected static ?string $pluralModelLabel = 'Laporan HSE';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_hse-report') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Laporan')
                    ->schema([
                        Forms\Components\DatePicker::make('report_date')
                            ->label('Tanggal Laporan')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('report_type')
                            ->label('Jenis Laporan')
                            ->options([
                                'daily' => 'Harian',
                                'weekly' => 'Mingguan',
                                'monthly' => 'Bulanan',
                                'incident' => 'Insiden',
                                'induction' => 'Induksi',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Laporan')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi / Isi Laporan')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Lampiran File')
                            ->disk('public')
                            ->directory('hse-reports'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(40)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'incident' => 'Insiden',
                        'induction' => 'Induksi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'daily' => 'gray',
                        'weekly' => 'info',
                        'monthly' => 'primary',
                        'incident' => 'danger',
                        'induction' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->placeholder('-'),
            ])
            ->defaultSort('report_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('report_type')
                    ->label('Jenis')
                    ->options([
                        'daily' => 'Harian',
                        'weekly' => 'Mingguan',
                        'monthly' => 'Bulanan',
                        'incident' => 'Insiden',
                        'induction' => 'Induksi',
                    ]),
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name')
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
            'index' => Pages\ListHseReports::route('/'),
            'create' => Pages\CreateHseReport::route('/create'),
            'view' => Pages\ViewHseReport::route('/{record}'),
            'edit' => Pages\EditHseReport::route('/{record}/edit'),
        ];
    }
}
