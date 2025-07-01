<?php

namespace App\Filament\Resources\TailwindConfigResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TailwindConfigResource;

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