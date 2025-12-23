<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Process as SymfonyProcess;
use UnitEnum;
use BackedEnum;

class SystemMaintenance extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Maintenance & Update';

    protected static ?string $title = 'System Maintenance';

    protected static ?string $slug = 'system-maintenance';

    protected static UnitEnum|string|null $navigationGroup = 'Setting';


    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.system-maintenance';

    public ?string $commandOutput = null;

    public function clearCache(): void
    {
        try {
            Artisan::call('optimize:clear');
            Notification::make()
                ->title('Cache Cleared')
                ->body('Application cache has been cleared successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to clear cache: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function migrateDatabase(): void
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            Notification::make()
                ->title('Database Migrated')
                ->body('Migration command executed.')
                ->success()
                ->send();

            $this->commandOutput = "Migrate Output:\n" . $output;

        } catch (\Exception $e) {
            Notification::make()
                ->title('Migration Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function linkStorage(): void
    {
        try {
            Artisan::call('storage:link');
            Notification::make()
                ->title('Storage Linked')
                ->body('The [public/storage] directory has been linked.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function gitPull(): void
    {
        $this->commandOutput = "Running git pull...\n";

        try {
            // Using Symfony Process for better control over working directory and environment
            // Adjust the path if necessary, standard assumes document root
            $process = new SymfonyProcess(['git', 'pull', 'origin', 'main']);
            $process->setWorkingDirectory(base_path());
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $this->commandOutput .= $output;

                Notification::make()
                    ->title('Update Successful')
                    ->body('Git pull command executed successfully.')
                    ->success()
                    ->send();

                // Optionally verify if composer install is needed
                if (file_exists(base_path('composer.lock'))) {
                    // We could run composer install here too, but that's very heavy and risky.
                    // Just suggest it.
                    $this->commandOutput .= "\n[INFO] If composer.lock changed, remember to run 'composer install' manually via SSH if needed.";
                }

            } else {
                $error = $process->getErrorOutput();
                $this->commandOutput .= "Error:\n" . $error;

                Notification::make()
                    ->title('Update Failed')
                    ->body('Git pull failed. Check output for details.')
                    ->danger()
                    ->send();
            }

        } catch (\Exception $e) {
            $this->commandOutput .= "Exception: " . $e->getMessage();

            Notification::make()
                ->title('Exception')
                ->body('An error occurred while trying to run git pull.')
                ->danger()
                ->send();
        }
    }
}
