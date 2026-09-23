<?php

namespace Matondojk\FilamentCalendar\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishResourceCommand extends Command
{
    protected $signature = 'filament-calendar:publish-resource';
    protected $description = 'Publish the EventResource to your application';

    public function handle()
    {
        $source = __DIR__ . '/../Resources/Events';
        $destination = app_path('Filament/Resources/EventResource');

        if (File::exists($destination)) {
            if (!$this->confirm('The EventResource already exists. Do you want to overwrite it?')) {
                return;
            }
        }

        File::copyDirectory($source, $destination);

        // Update namespaces in all copied files
        $files = File::allFiles($destination);
        foreach ($files as $file) {
            $contents = File::get($file);
            $contents = str_replace(
                'namespace Matondojk\FilamentCalendar\Resources\Events',
                'namespace App\Filament\Resources\EventResource',
                $contents
            );
            $contents = str_replace(
                'use Matondojk\FilamentCalendar\Resources\Events\\',
                'use App\Filament\Resources\EventResource\\',
                $contents
            );
            File::put($file, $contents);
        }

        $this->info('EventResource published successfully to app/Filament/Resources/EventResource!');
        $this->info('You can now customize the form, tables, and pages as you wish.');
    }
}
