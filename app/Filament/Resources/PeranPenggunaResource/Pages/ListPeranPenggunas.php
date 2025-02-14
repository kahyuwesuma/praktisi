<?php

namespace App\Filament\Resources\PeranPenggunaResource\Pages;

use App\Filament\Resources\PeranPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeranPenggunas extends ListRecords
{
    protected static string $resource = PeranPenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
