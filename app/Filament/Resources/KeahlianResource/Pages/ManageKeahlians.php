<?php

namespace App\Filament\Resources\KeahlianResource\Pages;

use App\Filament\Resources\KeahlianResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKeahlians extends ManageRecords
{
    protected static string $resource = KeahlianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
