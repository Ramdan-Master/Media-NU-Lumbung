<?php

namespace App\Filament\Editor\Resources\NewsResource\Pages;

use App\Filament\Editor\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-assign area based on editor's area
        if (auth()->user()->isEditor() && auth()->user()->area_id) {
            $data['area_id'] = auth()->user()->area_id;
            $data['author_id'] = auth()->id();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
