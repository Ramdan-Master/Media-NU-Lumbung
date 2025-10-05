<?php

namespace App\Filament\Editor\Resources\NewsResource\Pages;

use App\Filament\Editor\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure area remains the same for editors
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
