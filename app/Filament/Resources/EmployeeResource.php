<?php

namespace App\Filament\Resources;

use App\Enums\EmploymentStatus;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'HRD';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?string $modelLabel = 'Karyawan';

    protected static ?string $pluralModelLabel = 'Karyawan';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_employee') ?? false;
    }

    public static function generateNextNip(): string
    {
        $max = Employee::query()->max('employee_no');

        $number = $max ? ((int) Str::afterLast($max, '-')) + 1 : 1;

        return 'NIP-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Akun Login')
                    ->description('Pilih akun pengguna yang sudah dibuat di Manajemen Akun. Satu akun hanya bisa terikat satu karyawan.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Pengguna')
                            ->relationship('user', 'name', fn (Builder $query) => $query->whereDoesntHave('employee'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Section::make('Data Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('employee_no')
                            ->label('Nomor Pegawai (NIP)')
                            ->required()
                            ->maxLength(30)
                            ->unique(ignoreRecord: true)
                            ->default(fn () => static::generateNextNip())
                            ->helperText('Otomatis terisi NIP berikutnya, bisa diubah.'),
                        Forms\Components\Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('position')
                            ->label('Jabatan')
                            ->maxLength(100),
                        Forms\Components\Select::make('employment_status')
                            ->label('Status Kepegawaian')
                            ->options(fn () => collect(EmploymentStatus::cases())->mapWithKeys(
                                fn (EmploymentStatus $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(EmploymentStatus::Contract->value)
                            ->required(),
                        Forms\Components\DatePicker::make('join_date')
                            ->label('Tanggal Bergabung'),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Tanggal Lahir'),
                        Forms\Components\Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options(fn () => collect(Gender::cases())->mapWithKeys(
                                fn (Gender $c) => [$c->value => $c->label()]
                            )->all()),
                        Forms\Components\Select::make('marital_status')
                            ->label('Status Pernikahan')
                            ->options(fn () => collect(MaritalStatus::cases())->mapWithKeys(
                                fn (MaritalStatus $c) => [$c->value => $c->label()]
                            )->all()),
                    ])
                    ->columns(3),
                Section::make('Data Administratif')
                    ->schema([
                        Forms\Components\TextInput::make('ktp_no')
                            ->label('No. KTP')
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('npwp_no')
                            ->label('No. NPWP')
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('bpjs_kes')
                            ->label('No. BPJS Kesehatan')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('bpjs_tk')
                            ->label('No. BPJS Ketenagakerjaan')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Nama Bank')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('bank_account_no')
                            ->label('No. Rekening')
                            ->maxLength(30),
                        Forms\Components\TextInput::make('emergency_contact')
                            ->label('Kontak Darurat')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('user.photo')
                    ->label('')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('employee_no')
                    ->label('NIP')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departemen')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employment_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (EmploymentStatus $state) => $state->label())
                    ->color(fn (EmploymentStatus $state): string => match ($state) {
                        EmploymentStatus::Permanent => 'success',
                        EmploymentStatus::Contract => 'warning',
                        EmploymentStatus::Internship => 'info',
                    }),
                Tables\Columns\TextColumn::make('join_date')
                    ->label('Gabung')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->defaultSort('employee_no')
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('employment_status')
                    ->label('Status Kepegawaian')
                    ->options(fn () => collect(EmploymentStatus::cases())->mapWithKeys(
                        fn (EmploymentStatus $c) => [$c->value => $c->label()]
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
