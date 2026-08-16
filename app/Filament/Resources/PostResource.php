<?php

namespace App\Filament\Resources;

use App\Enums\PostCategory;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_any_post') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Konten Berita')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(220)
                            ->helperText('Otomatis terisi dari judul'),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options(fn () => collect(PostCategory::cases())->mapWithKeys(
                                fn (PostCategory $c) => [$c->value => $c->label()]
                            )->all())
                            ->default(PostCategory::News->value)
                            ->required(),
                        Forms\Components\Select::make('author_id')
                            ->label('Penulis')
                            ->relationship('author', 'name')
                            ->default(fn () => auth()->id())
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Ringkasan (Excerpt)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('body')
                            ->label('Isi Berita')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Gambar Cover')
                            ->disk('public')
                            ->directory('posts/covers')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Dipublikasikan'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Waktu Publikasi')
                            ->default(now()),
                    ])
                    ->columns(2),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(45)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (PostCategory $state) => $state->label())
                    ->color(fn (PostCategory $state) => $state->color()),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => collect(PostCategory::cases())->mapWithKeys(
                        fn (PostCategory $c) => [$c->value => $c->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('is_published')
                    ->label('Status Publikasi')
                    ->options([true => 'Terbit', false => 'Draf']),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
