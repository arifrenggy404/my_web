<?php

namespace App\Filament\Resources;

use App\Models\Pengaturan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class PengaturanResource extends Resource
{
    protected static ?string $model = Pengaturan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Kelola Pengaturan';

    protected static ?string $modelLabel = 'Pengaturan';

    protected static ?string $pluralModelLabel = 'Pengaturan Global';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('nama_aplikasi')
                            ->label('Nama Aplikasi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('versi')
                            ->label('Versi Sistem')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Tampilan Header')
                    ->schema([
                        Forms\Components\Textarea::make('ascii_header')
                            ->label('ASCII Art Header')
                            ->required()
                            ->rows(8)
                            ->extraAttributes(['class' => 'font-mono']),
                    ]),

                Section::make('Konten Halaman')
                    ->schema([
                        Forms\Components\Textarea::make('analisis_tambahan_arsenal')
                            ->label('Analisis Tambahan Halaman Arsenal')
                            ->required()
                            ->rows(4),

                        Forms\Components\Textarea::make('catatan_pengembang_kontak')
                            ->label('Catatan Pengembang Halaman Kontak')
                            ->required()
                            ->rows(4),

                        Forms\Components\TextInput::make('waktu_respons_kontak')
                            ->label('Waktu Respons Kontak')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_aplikasi')
                    ->label('Nama Aplikasi'),
                Tables\Columns\TextColumn::make('versi')
                    ->label('Versi'),
                Tables\Columns\TextColumn::make('waktu_respons_kontak')
                    ->label('Waktu Respons'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for singleton
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PengaturanResource\Pages\ManagePengaturans::route('/'),
        ];
    }
}
