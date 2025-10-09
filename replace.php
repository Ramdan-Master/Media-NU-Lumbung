<?php

$files = glob('app/**/*.php');

foreach ($files as $file) {

    $content = file_get_contents($file);

$content = str_replace('use Filament\Resources\Resource;', 'use BackedEnum;' . PHP_EOL . 'use Filament\Resources\Resource;' . PHP_EOL . 'use Filament\Schemas\Schema;' . PHP_EOL . 'use UnitEnum;', $content);

$content = str_replace('protected static ?string $navigationGroup', 'protected static UnitEnum|string|null $navigationGroup', $content);

$content = str_replace('protected static ?string $navigationIcon', 'protected static BackedEnum|string|null $navigationIcon', $content);

$content = str_replace('public static function form(Form $form): Form', 'public static function form(Schema $schema): Schema', $content);

$content = str_replace('return $form', 'return $schema', $content);

    file_put_contents($file, $content);

}

echo "Done";
