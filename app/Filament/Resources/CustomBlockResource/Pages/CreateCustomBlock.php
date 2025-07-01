<?php

namespace App\Filament\Resources\CustomBlockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomBlockResource;

class CreateCustomBlock extends CreateRecord
{
    protected static string $resource = CustomBlockResource::class;
}
