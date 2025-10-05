<?php

namespace App\Filament\Editor\Resources\TagResource\Pages;

use App\Filament\Editor\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTag extends ViewRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
