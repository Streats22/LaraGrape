<?php

namespace App\Filament\Resources\CustomBlockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CustomBlockResource;

class EditCustomBlock extends EditRecord
{
    protected static string $resource = CustomBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
