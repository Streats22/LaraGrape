<?php

namespace App\Filament\Resources\CustomBlockResource\Pages;

use App\Filament\Resources\CustomBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomBlock extends CreateRecord
{
    protected static string $resource = CustomBlockResource::class;
}
