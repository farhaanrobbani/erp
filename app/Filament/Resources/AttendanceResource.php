<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'HRD';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $modelLabel = 'Absensi';

    protected static ?string $pluralModelLabel = 'Absensi';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_attendance') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Absensi')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Karyawan')
                            ->relationship('employee', 'employee_no')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->employee_no . ' — ' . $record->user->name)
                            ->searchable(['employee_no', 'user.name'])
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('work_date')
                            ->label('Tanggal Kerja')
                            ->required()
                            ->rules([
                                fn (Get $get, ?string $operation, $record): \Closure => function (string $attribute, $value, \Closure $fail) use ($get, $operation, $record) {
                                    $employeeId = $get('employee_id');
                                    if (!$employeeId) {
                                        return;
                                    }
                                    
                                    $query = Attendance::where('employee_id', $employeeId)
                                        ->where('work_date', $value);
                                    
                                    if ($operation === 'edit' && $record) {
                                        $query->where('id', '!=', $record->id);
                                    }
                                    
                                    if ($query->exists()) {
                                        $fail('Karyawan ini sudah memiliki absensi pada tanggal tersebut.');
                                    }
                                },
                            ]),
                        Forms\Components\DateTimePicker::make('check_in')
                            ->label('Jam Masuk')
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('check_out')
                            ->label('Jam Keluar')
                            ->seconds(false),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(AttendanceStatus::cases())->mapWithKeys(
                                fn (AttendanceStatus $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(AttendanceStatus::Present->value)
                            ->required(),
                        Forms\Components\Select::make('work_location_id')
                            ->label('Lokasi Kerja')
                            ->relationship('workLocation', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(3),
                Section::make('Foto & Geolokasi')
                    ->schema([
                        Forms\Components\FileUpload::make('check_in_photo')
                            ->label('Foto Check-In')
                            ->disk('public')
                            ->directory('attendance-photos')
                            ->image()
                            ->imageEditor(),
                        Forms\Components\FileUpload::make('check_out_photo')
                            ->label('Foto Check-Out')
                            ->disk('public')
                            ->directory('attendance-photos')
                            ->image()
                            ->imageEditor(),
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->helperText('Koordinat GPS lokasi absensi'),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric(),
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
                Tables\Columns\TextColumn::make('work_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->label('Jam Masuk')
                    ->time('H:i')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('check_out')
                    ->label('Jam Keluar')
                    ->time('H:i')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (AttendanceStatus $state) => $state->label())
                    ->color(fn (AttendanceStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('workLocation.name')
                    ->label('Lokasi')
                    ->placeholder('-'),
            ])
            ->defaultSort('work_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(fn () => collect(AttendanceStatus::cases())->mapWithKeys(
                        fn (AttendanceStatus $c) => [$c->value => $c->label()]
                    )->all()),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
