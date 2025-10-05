<?php

namespace App\Filament\Editor\Resources\CategoryResource\Pages;

use App\Filament\Editor\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
