<?php

namespace App\Filament\Resources\TailwindConfigResource\Pages;

use App\Filament\Resources\TailwindConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTailwindConfig extends EditRecord
{
    protected static string $resource = TailwindConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 