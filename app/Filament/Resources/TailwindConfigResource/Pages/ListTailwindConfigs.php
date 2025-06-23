<?php

namespace App\Filament\Resources\TailwindConfigResource\Pages;

use App\Filament\Resources\TailwindConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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