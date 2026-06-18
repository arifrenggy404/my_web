<?php

namespace App\Filament\Resources;

use App\Models\Keahlian;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class KeahlianResource extends Resource
{
    protected static ?string $model = Keahlian::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Kelola Keahlian';

    protected static ?string $modelLabel = 'Keahlian';

    protected static ?string $pluralModelLabel = 'Daftar Keahlian';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Teknologi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('level')
                            ->label('Tingkat Keahlian (%)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100),

                        Forms\Components\Select::make('warna')
                            ->label('Warna Indikator')
                            ->required()
                            ->options([
                                'primary' => 'Primary (Cyan)',
                                'accent' => 'Accent (Pink)',
                                'warning' => 'Warning (Yellow)',
                            ]),

                        Forms\Components\Toggle::make('apakah_core')
                            ->label('Core Engine Teknologi')
                            ->default(false),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Singkat')
                            ->nullable()
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Teknologi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Tingkat (%)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('warna')
                    ->label('Warna')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accent' => 'danger',
                        'warning' => 'warning',
                        default => 'info',
                    }),
                Tables\Columns\IconColumn::make('apakah_core')
                    ->label('Core')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => KeahlianResource\Pages\ManageKeahlians::route('/'),
        ];
    }
}
