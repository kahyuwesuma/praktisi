<?php

namespace App\Filament\Resources\PeranPenggunaResource\Pages;

use App\Filament\Resources\PeranPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeranPengguna extends EditRecord
{
    protected static string $resource = PeranPenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
