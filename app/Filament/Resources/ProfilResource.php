<?php

namespace App\Filament\Resources;

use App\Models\Profil;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ProfilResource extends Resource
{
    protected static ?string $model = Profil::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Kelola Profil';

    protected static ?string $modelLabel = 'Profil';

    protected static ?string $pluralModelLabel = 'Daftar Profil';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('peran')
                            ->label('Peran / Jabatan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('spesialisasi')
                            ->label('Spesialisasi Utama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('wilayah')
                            ->label('Wilayah / Domisili')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('kutipan')
                            ->label('Kutipan Profil')
                            ->required()
                            ->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('peran')
                    ->label('Peran'),
                Tables\Columns\TextColumn::make('spesialisasi')
                    ->label('Spesialisasi'),
                Tables\Columns\TextColumn::make('wilayah')
                    ->label('Wilayah'),
            ])
            ->actions([
                Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ProfilResource\Pages\ManageProfils::route('/'),
        ];
    }
}
