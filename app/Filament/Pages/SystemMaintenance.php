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

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Superadmin');
    }

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
            // Determine the correct path to the git executable if not in PATH
            // On standard cPanel/Niagahoster, 'git' usually works if /usr/bin is in path.

            // CRITICAL: Set HOME environment variable. 
            // git needs to know where ~/.ssh is located. 
            // In web processes (www-data/nobody), HOME might not be set to the user's home.
            // We assume standard cPanel structure: /home/username
            $homeDir = $_SERVER['HOME'] ?? base_path('../..');
            // Fallback attempt to guess home if $_SERVER['HOME'] is empty (common in some php-fpm configs)
            if (empty($_SERVER['HOME'])) {
                // If base_path is /home/user/public_html, go up two levels
                $homeDir = realpath(base_path('../../'));
            }

            $process = new SymfonyProcess(['git', 'pull', 'origin', 'main']);
            $process->setWorkingDirectory(base_path());

            // Pass environment variables
            $process->setEnv([
                'HOME' => $homeDir,
                'COMPOSER_HOME' => "$homeDir/.composer", // Good practice
            ]);

            // Set timeout to 60 seconds
            $process->setTimeout(60);

            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $this->commandOutput .= $output;

                Notification::make()
                    ->title('Update Successful')
                    ->body('Git pull command executed successfully.')
                    ->success()
                    ->send();

                // Check for composer.lock changes
                if (str_contains($output, 'composer.lock')) {
                    $this->commandOutput .= "\n[WARN] composer.lock changed! You might need to run 'composer install' via SSH manually if you encounter errors.";
                }

                // Check for migrations
                if (str_contains($output, 'database/migrations')) {
                    $this->commandOutput .= "\n[INFO] New migrations detected. Don't forget to click 'Migrate Database'!";
                }

            } else {
                $error = $process->getErrorOutput();
                $output = $process->getOutput(); // Sometimes error is in stdout

                $this->commandOutput .= "EXIT CODE: " . $process->getExitCode() . "\n";
                $this->commandOutput .= "ERROR (stderr):\n" . $error . "\n";
                $this->commandOutput .= "OUTPUT (stdout):\n" . $output;

                Notification::make()
                    ->title('Update Failed')
                    ->body('Git pull failed. Please check the output log below.')
                    ->danger()
                    ->send();
            }

        } catch (\Exception $e) {
            $this->commandOutput .= "Exception: " . $e->getMessage();

            Notification::make()
                ->title('Exception')
                ->body('An error occurred while executing git pull.')
                ->danger()
                ->send();
        }
    }
}
