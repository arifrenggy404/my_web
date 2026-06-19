<?php

namespace App\Filament\Resources;

use App\Models\Kontak;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class KontakResource extends Resource
{
    protected static ?string $model = Kontak::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Kelola Kontak';

    protected static ?string $modelLabel = 'Kontak';

    protected static ?string $pluralModelLabel = 'Daftar Kontak';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Platform')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('username_label')
                            ->label('Username / Label Teks')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('tautan_url')
                            ->label('Tautan URL')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('tipe_ikon')
                            ->label('Tipe Ikon')
                            ->required()
                            ->options([
                                'github' => 'GitHub',
                                'linkedin' => 'LinkedIn',
                                'whatsapp' => 'WhatsApp',
                                'email' => 'Email (Mailto)',
                                'website' => 'Website / Globe',
                            ]),

                        Forms\Components\Select::make('warna_hover')
                            ->label('Warna Hover Indikator')
                            ->required()
                            ->options([
                                'primary' => 'Primary (Cyan)',
                                'accent' => 'Accent (Pink)',
                                'warning' => 'Warning (Yellow)',
                                'white' => 'White',
                            ]),

                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Platform')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('username_label')
                    ->label('Label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tautan_url')
                    ->label('URL')
                    ->limit(30),
                Tables\Columns\TextColumn::make('tipe_ikon')
                    ->label('Ikon'),
                Tables\Columns\TextColumn::make('warna_hover')
                    ->label('Warna Hover')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accent' => 'danger',
                        'warning' => 'warning',
                        'white' => 'gray',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => KontakResource\Pages\ManageKontaks::route('/'),
        ];
    }
}
