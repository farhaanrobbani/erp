<?php

namespace App\Filament\Pages;

use App\Models\CompanyProfile;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageWebsiteSettings extends Page
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 7;

    protected static ?string $slug = 'website/pengaturan';

    protected static ?string $title = 'Pengaturan Website';

    protected string $view = 'filament.pages.manage-website-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('update_company-profile') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'general_company_name' => CompanyProfile::value('general.company_name'),
            'general_slogan' => CompanyProfile::value('general.slogan'),
            'general_established_year' => CompanyProfile::value('general.established_year'),
            'general_logo' => CompanyProfile::value('general.logo'),
            'hero_badge' => CompanyProfile::value('hero.badge'),
            'hero_title' => CompanyProfile::value('hero.title'),
            'hero_title_highlight' => CompanyProfile::value('hero.title_highlight'),
            'hero_subtitle' => CompanyProfile::value('hero.subtitle'),
            'hero_cta_primary' => CompanyProfile::value('hero.cta_primary'),
            'hero_cta_secondary' => CompanyProfile::value('hero.cta_secondary'),
            'hero_stats' => CompanyProfile::jsonValue('hero.stats'),
            'about_title' => CompanyProfile::value('about.title'),
            'about_body' => CompanyProfile::value('about.body'),
            'about_points' => $this->toRepeaterItems(CompanyProfile::jsonValue('about.points')),
            'about_stats' => CompanyProfile::jsonValue('about.stats'),
            'services' => CompanyProfile::jsonValue('services'),
            'hse_title' => CompanyProfile::value('hse.title'),
            'hse_body' => CompanyProfile::value('hse.body'),
            'hse_stats' => CompanyProfile::jsonValue('hse.stats'),
            'hse_cert_label' => CompanyProfile::value('hse.cert_label'),
            'contact_address' => CompanyProfile::value('contact.address'),
            'contact_phone' => CompanyProfile::value('contact.phone'),
            'contact_email' => CompanyProfile::value('contact.email'),
            'contact_social_instagram' => CompanyProfile::value('contact.social_instagram'),
            'contact_social_linkedin' => CompanyProfile::value('contact.social_linkedin'),
            'contact_social_facebook' => CompanyProfile::value('contact.social_facebook'),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanyProfile::setValue('general.company_name', $data['general_company_name'], 'general');
        CompanyProfile::setValue('general.slogan', $data['general_slogan'], 'general');
        CompanyProfile::setValue('general.established_year', $data['general_established_year'], 'general');
        CompanyProfile::setValue('general.logo', $data['general_logo'] ?? '', 'general');

        CompanyProfile::setValue('hero.badge', $data['hero_badge'], 'hero');
        CompanyProfile::setValue('hero.title', $data['hero_title'], 'hero');
        CompanyProfile::setValue('hero.title_highlight', $data['hero_title_highlight'], 'hero');
        CompanyProfile::setValue('hero.subtitle', $data['hero_subtitle'], 'hero');
        CompanyProfile::setValue('hero.cta_primary', $data['hero_cta_primary'], 'hero');
        CompanyProfile::setValue('hero.cta_secondary', $data['hero_cta_secondary'], 'hero');
        CompanyProfile::setJsonValue('hero.stats', $data['hero_stats'], 'hero');

        CompanyProfile::setValue('about.title', $data['about_title'], 'about');
        CompanyProfile::setValue('about.body', $data['about_body'], 'about');
        CompanyProfile::setJsonValue('about.points', collect($data['about_points'])->pluck('point')->values()->all(), 'about');
        CompanyProfile::setJsonValue('about.stats', $data['about_stats'], 'about');

        CompanyProfile::setJsonValue('services', $data['services'], 'services');

        CompanyProfile::setValue('hse.title', $data['hse_title'], 'hse');
        CompanyProfile::setValue('hse.body', $data['hse_body'], 'hse');
        CompanyProfile::setJsonValue('hse.stats', $data['hse_stats'], 'hse');
        CompanyProfile::setValue('hse.cert_label', $data['hse_cert_label'], 'hse');

        CompanyProfile::setValue('contact.address', $data['contact_address'], 'contact');
        CompanyProfile::setValue('contact.phone', $data['contact_phone'], 'contact');
        CompanyProfile::setValue('contact.email', $data['contact_email'], 'contact');
        CompanyProfile::setValue('contact.social_instagram', $data['contact_social_instagram'], 'contact');
        CompanyProfile::setValue('contact.social_linkedin', $data['contact_social_linkedin'], 'contact');
        CompanyProfile::setValue('contact.social_facebook', $data['contact_social_facebook'], 'contact');

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }

    protected function toRepeaterItems(array $items): array
    {
        return collect($items)->map(fn (string $item): array => ['point' => $item])->all();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Identitas Perusahaan')
                    ->schema([
                        TextInput::make('general_company_name')->label('Nama Perusahaan')->required(),
                        TextInput::make('general_slogan')->label('Slogan')->maxLength(255),
                        TextInput::make('general_established_year')->label('Tahun Berdiri')->maxLength(10),
                        FileUpload::make('general_logo')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('brand')
                            ->image()
                            ->imageEditor()
                            ->helperText('Format PNG/SVG, disarankan latar transparan. Kosongkan untuk fallback teks.')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Hero (Beranda)')
                    ->schema([
                        TextInput::make('hero_badge')->label('Badge Atas')->helperText('Teks kecil di atas judul hero'),
                        TextInput::make('hero_title')->label('Judul (bagian 1)')->helperText('Warna teks normal'),
                        TextInput::make('hero_title_highlight')->label('Judul (bagian sorotan)')->helperText('Warna biru'),
                        Textarea::make('hero_subtitle')->label('Sub Judul')->rows(3),
                        TextInput::make('hero_cta_primary')->label('Teks Tombol Utama'),
                        TextInput::make('hero_cta_secondary')->label('Teks Tombol Kedua'),
                        Repeater::make('hero_stats')
                            ->label('Statistik Hero')
                            ->schema([
                                TextInput::make('value')->label('Angka')->required(),
                                TextInput::make('label')->label('Keterangan')->required(),
                            ])
                            ->defaultItems(4)
                            ->reorderable()
                            ->columns(2),
                    ])
                    ->columns(2),
                Section::make('Tentang Kami')
                    ->schema([
                        TextInput::make('about_title')->label('Judul')->required(),
                        Textarea::make('about_body')->label('Isi')->rows(5),
                        Repeater::make('about_points')
                            ->label('Poin Keunggulan')
                            ->schema([
                                TextInput::make('point')->label('Poin')->required(),
                            ])
                            ->defaultItems(4)
                            ->reorderable(),
                        Repeater::make('about_stats')
                            ->label('Statistik Tentang')
                            ->schema([
                                TextInput::make('value')->label('Angka')->required(),
                                TextInput::make('label')->label('Keterangan')->required(),
                            ])
                            ->defaultItems(4)
                            ->reorderable()
                            ->columns(2),
                    ]),
                Section::make('Layanan / Bidang Usaha')
                    ->schema([
                        Repeater::make('services')
                            ->label('Daftar Layanan')
                            ->schema([
                                TextInput::make('title')->label('Nama Layanan')->required(),
                                Textarea::make('desc')->label('Deskripsi')->rows(3)->required(),
                            ])
                            ->defaultItems(3)
                            ->reorderable()
                            ->columns(2),
                    ]),
                Section::make('HSE / K3')
                    ->schema([
                        TextInput::make('hse_title')->label('Judul')->required(),
                        Textarea::make('hse_body')->label('Isi')->rows(4),
                        Repeater::make('hse_stats')
                            ->label('Statistik K3')
                            ->schema([
                                TextInput::make('value')->label('Angka')->required(),
                                TextInput::make('label')->label('Keterangan')->required(),
                            ])
                            ->defaultItems(2)
                            ->reorderable()
                            ->columns(2),
                        TextInput::make('hse_cert_label')->label('Label Sertifikasi'),
                    ]),
                Section::make('Kontak & Sosial Media')
                    ->schema([
                        TextInput::make('contact_address')->label('Alamat'),
                        TextInput::make('contact_phone')->label('Telepon'),
                        TextInput::make('contact_email')->label('Email')->email(),
                        TextInput::make('contact_social_instagram')->label('Instagram (URL)')->url(),
                        TextInput::make('contact_social_linkedin')->label('LinkedIn (URL)')->url(),
                        TextInput::make('contact_social_facebook')->label('Facebook (URL)')->url(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Simpan Pengaturan')
                ->icon('heroicon-m-check-circle')
                ->submit('save'),
        ];
    }
}
