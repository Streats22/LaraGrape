<?php

namespace App\Filament\Resources\TailwindConfigResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TailwindConfigResource;

class ListTailwindConfigs extends ListRecords
{
    protected static string $resource = TailwindConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 