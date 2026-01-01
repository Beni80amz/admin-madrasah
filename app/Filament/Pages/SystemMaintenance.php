<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
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

    // Properties
    public ?string $commandOutput = null;
    public ?array $versionInfo = null;
    public ?array $updateInfo = null;
    public ?array $systemInfo = null;
    public bool $isUpdating = false;

    public function mount(): void
    {
        $this->loadVersionInfo();
        $this->loadSystemInfo();
    }

    /**
     * Load current version information
     */
    public function loadVersionInfo(): void
    {
        $basePath = base_path();

        try {
            $this->versionInfo = [
                'current_version' => trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* rev-parse --short HEAD 2>&1") ?? 'N/A'),
                'branch' => trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* branch --show-current 2>&1") ?? 'main'),
                'last_commit_date' => trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* log -1 --format=%ci 2>&1") ?? 'N/A'),
                'last_commit_message' => trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* log -1 --pretty=%B 2>&1") ?? 'N/A'),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ];
        } catch (\Exception $e) {
            $this->versionInfo = [
                'current_version' => 'Error',
                'branch' => 'N/A',
                'last_commit_date' => 'N/A',
                'last_commit_message' => $e->getMessage(),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ];
        }
    }

    /**
     * Load system information (check Composer, NPM, Git availability)
     */
    public function loadSystemInfo(): void
    {
        $this->systemInfo = [
            'git' => $this->checkCommand('git --version'),
            'composer' => $this->checkCommand('composer --version'),
            'npm' => $this->checkCommand('npm --version'),
            'node' => $this->checkCommand('node --version'),
            'disk_free' => $this->getDiskSpace(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Enabled' : 'Disabled',
        ];
    }

    /**
     * Check if a command is available
     */
    protected function checkCommand(string $command): array
    {
        try {
            $output = shell_exec($command . ' 2>&1');
            $available = !empty($output) && !str_contains(strtolower($output), 'not found') && !str_contains(strtolower($output), 'not recognized');

            return [
                'available' => $available,
                'version' => $available ? trim($output) : 'Not installed',
            ];
        } catch (\Exception $e) {
            return [
                'available' => false,
                'version' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get disk space information
     */
    protected function getDiskSpace(): string
    {
        try {
            $free = disk_free_space(base_path());
            $total = disk_total_space(base_path());
            $used = $total - $free;

            return sprintf(
                '%s / %s (%.1f%% used)',
                $this->formatBytes($used),
                $this->formatBytes($total),
                ($used / $total) * 100
            );
        } catch (\Exception $e) {
            return 'Unable to determine';
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
    }

    /**
     * Check for available updates
     */
    public function checkForUpdates(): void
    {
        $basePath = base_path();

        try {
            // Fetch from remote
            $process = new SymfonyProcess(['git', '-c', 'safe.directory=*', 'fetch', 'origin']);
            $process->setWorkingDirectory($basePath);
            $process->setEnv([
                'HOME' => $this->getHomeDirectory(),
                'GIT_SSH_COMMAND' => 'ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no',
            ]);
            $process->setTimeout(60);
            $process->run();

            // Get branch name
            // Note: shell_exec calls don't easily accept env vars without complex string manipulation.
            // However, 'git branch' and 'git rev-parse' are local operations and don't need SSH keys.
            // 'git fetch' is the only one here that talks to the remote.

            $branch = trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* branch --show-current 2>&1") ?? 'main');

            // Count commits behind
            $behindCount = (int) trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* rev-list HEAD..origin/{$branch} --count 2>&1") ?? '0');

            // Get pending commits
            $pendingUpdates = [];
            if ($behindCount > 0) {
                // git log origin/branch assumes we have fetched. It's local now.
                $logOutput = shell_exec("cd \"{$basePath}\" && git -c safe.directory=* log HEAD..origin/{$branch} --pretty=format:\"%h - %s (%cr)\" 2>&1");
                $pendingUpdates = array_filter(explode("\n", $logOutput ?? ''));
            }

            // Get current and latest commit
            $currentCommit = trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* rev-parse --short HEAD 2>&1") ?? 'N/A');
            $latestCommit = trim(shell_exec("cd \"{$basePath}\" && git -c safe.directory=* rev-parse --short origin/{$branch} 2>&1") ?? 'N/A');

            $this->updateInfo = [
                'has_update' => $behindCount > 0,
                'pending_count' => $behindCount,
                'current_version' => $currentCommit,
                'latest_version' => $latestCommit,
                'pending_updates' => $pendingUpdates,
                'last_check' => now()->format('d M Y H:i:s'),
            ];

            if ($behindCount > 0) {
                Notification::make()
                    ->title('Update Tersedia!')
                    ->body("{$behindCount} commit baru tersedia untuk diupdate.")
                    ->warning()
                    ->send();
            } else {
                Notification::make()
                    ->title('Sistem Up-to-Date')
                    ->body('Tidak ada update yang tersedia.')
                    ->success()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Gagal memeriksa update: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Clear application cache
     */
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

    /**
     * Rebuild cache and optimize
     */
    public function optimizeApplication(): void
    {
        try {
            Artisan::call('optimize');
            Artisan::call('filament:cache-components');
            Artisan::call('icons:cache');

            Notification::make()
                ->title('Optimization Complete')
                ->body('Application has been optimized for production.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Optimization failed: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Run database migration
     */
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

    /**
     * Create storage link
     */
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

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenanceMode(): void
    {
        try {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
                Notification::make()
                    ->title('Maintenance Mode Disabled')
                    ->body('Website sekarang dapat diakses kembali.')
                    ->success()
                    ->send();
            } else {
                $secret = 'bypass-' . now()->timestamp;
                Artisan::call('down', ['--secret' => $secret]);
                Notification::make()
                    ->title('Maintenance Mode Enabled')
                    ->body("Website dalam mode maintenance. Bypass URL: /{$secret}")
                    ->warning()
                    ->persistent()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Run Composer Install
     */
    public function runComposerInstall(): void
    {
        $this->commandOutput = "Running composer install...\n";

        try {
            $homeDir = $this->getHomeDirectory();
            $process = new SymfonyProcess(['composer', 'install', '--no-dev', '--optimize-autoloader', '--no-interaction']);
            $process->setWorkingDirectory(base_path());
            $process->setEnv([
                'HOME' => $homeDir,
                'COMPOSER_HOME' => "$homeDir/.composer",
            ]);
            $process->setTimeout(300); // 5 minutes

            $process->run();

            if ($process->isSuccessful()) {
                $this->commandOutput .= $process->getOutput();
                Notification::make()
                    ->title('Composer Install Berhasil')
                    ->body('Dependencies telah diupdate.')
                    ->success()
                    ->send();
            } else {
                $this->commandOutput .= "ERROR:\n" . $process->getErrorOutput();
                Notification::make()
                    ->title('Composer Install Gagal')
                    ->body('Lihat output log untuk detail.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->commandOutput .= "Exception: " . $e->getMessage();
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Run NPM Install and Build
     */
    public function runNpmBuild(): void
    {
        $this->commandOutput = "Running npm install && npm run build...\n";

        try {
            $homeDir = $this->getHomeDirectory();
            $basePath = base_path();

            // NPM Install
            $process = new SymfonyProcess(['npm', 'install']);
            $process->setWorkingDirectory($basePath);
            $process->setEnv(['HOME' => $homeDir]);
            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->commandOutput .= "NPM Install Error:\n" . $process->getErrorOutput();
                Notification::make()
                    ->title('NPM Install Gagal')
                    ->body('Lihat output log untuk detail.')
                    ->danger()
                    ->send();
                return;
            }

            $this->commandOutput .= "NPM Install: OK\n";

            // NPM Build
            $process = new SymfonyProcess(['npm', 'run', 'build']);
            $process->setWorkingDirectory($basePath);
            $process->setEnv(['HOME' => $homeDir]);
            $process->setTimeout(300);
            $process->run();

            if ($process->isSuccessful()) {
                $this->commandOutput .= "NPM Build: OK\n" . $process->getOutput();
                Notification::make()
                    ->title('Build Assets Berhasil')
                    ->body('Frontend assets telah di-build ulang.')
                    ->success()
                    ->send();
            } else {
                $this->commandOutput .= "NPM Build Error:\n" . $process->getErrorOutput();
                Notification::make()
                    ->title('NPM Build Gagal')
                    ->body('Lihat output log untuk detail.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->commandOutput .= "Exception: " . $e->getMessage();
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Perform full update (Git Pull + Composer + NPM + Migrate + Optimize)
     */
    public function performFullUpdate(): void
    {
        $this->isUpdating = true;
        $this->commandOutput = "=== FULL UPDATE STARTED ===\n";
        $this->commandOutput .= "Time: " . now()->format('Y-m-d H:i:s') . "\n\n";

        try {
            $homeDir = $this->getHomeDirectory();
            $basePath = base_path();

            // Step 1: Enable maintenance mode
            $this->commandOutput .= "[1/6] Enabling maintenance mode...\n";
            $secret = 'update-' . now()->timestamp;
            Artisan::call('down', ['--secret' => $secret]);
            $this->commandOutput .= "✓ Maintenance mode enabled (bypass: /{$secret})\n\n";

            // Step 2: Git Pull
            $this->commandOutput .= "[2/6] Pulling latest changes from Git...\n";
            $process = new SymfonyProcess(['git', '-c', 'safe.directory=*', 'pull', 'origin', $this->versionInfo['branch'] ?? 'main']);
            $process->setWorkingDirectory($basePath);
            $process->setEnv([
                'HOME' => $homeDir,
                'COMPOSER_HOME' => "$homeDir/.composer",
                'GIT_SSH_COMMAND' => 'ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no',
            ]);
            $process->setTimeout(300);
            $process->run();

            if ($process->isSuccessful()) {
                $gitOutput = $process->getOutput();
                $this->commandOutput .= "✓ Git pull successful\n{$gitOutput}\n";

                $composerChanged = str_contains($gitOutput, 'composer.lock') || str_contains($gitOutput, 'composer.json');
                $npmChanged = str_contains($gitOutput, 'package.json') || str_contains($gitOutput, 'package-lock.json');
                $migrationsChanged = str_contains($gitOutput, 'database/migrations');
            } else {
                $this->commandOutput .= "✗ Git pull failed:\n" . $process->getErrorOutput() . "\n";
                throw new \Exception('Git pull failed');
            }

            // Step 3: Composer Install (if needed or always for safety)
            $this->commandOutput .= "[3/6] Running Composer install...\n";
            if ($this->systemInfo['composer']['available'] ?? false) {
                $process = new SymfonyProcess(['composer', 'install', '--no-dev', '--optimize-autoloader', '--no-interaction']);
                $process->setWorkingDirectory($basePath);
                $process->setEnv(['HOME' => $homeDir, 'COMPOSER_HOME' => "$homeDir/.composer"]);
                $process->setTimeout(300);
                $process->run();

                if ($process->isSuccessful()) {
                    $this->commandOutput .= "✓ Composer install successful\n";
                } else {
                    $this->commandOutput .= "⚠ Composer warning (might need manual run):\n" . $process->getErrorOutput() . "\n";
                }
            } else {
                $this->commandOutput .= "⚠ Composer not available, skipping...\n";
            }

            // Step 4: NPM Build (if needed or always for safety)
            $this->commandOutput .= "\n[4/6] Building frontend assets...\n";
            if ($this->systemInfo['npm']['available'] ?? false) {
                // npm install
                $process = new SymfonyProcess(['npm', 'install']);
                $process->setWorkingDirectory($basePath);
                $process->setEnv(['HOME' => $homeDir]);
                $process->setTimeout(300);
                $process->run();

                // npm run build
                $process = new SymfonyProcess(['npm', 'run', 'build']);
                $process->setWorkingDirectory($basePath);
                $process->setEnv(['HOME' => $homeDir]);
                $process->setTimeout(300);
                $process->run();

                if ($process->isSuccessful()) {
                    $this->commandOutput .= "✓ NPM build successful\n";
                } else {
                    $this->commandOutput .= "⚠ NPM build warning:\n" . $process->getErrorOutput() . "\n";
                }
            } else {
                $this->commandOutput .= "⚠ NPM not available, skipping...\n";
            }

            // Step 5: Run Migrations
            $this->commandOutput .= "\n[5/6] Running database migrations...\n";
            Artisan::call('migrate', ['--force' => true]);
            $this->commandOutput .= "✓ Migrations completed\n" . Artisan::output();

            // Step 6: Optimize
            $this->commandOutput .= "\n[6/6] Optimizing application...\n";
            Artisan::call('optimize');
            Artisan::call('filament:cache-components');
            Artisan::call('icons:cache');
            $this->commandOutput .= "✓ Application optimized\n";

            // Disable maintenance mode
            Artisan::call('up');
            $this->commandOutput .= "\n✓ Maintenance mode disabled\n";

            // Reload version info
            $this->loadVersionInfo();
            $this->updateInfo = null;

            $this->commandOutput .= "\n=== UPDATE COMPLETED SUCCESSFULLY ===\n";
            $this->commandOutput .= "New version: " . ($this->versionInfo['current_version'] ?? 'N/A') . "\n";

            Notification::make()
                ->title('Update Berhasil!')
                ->body('Aplikasi telah diupdate ke versi terbaru.')
                ->success()
                ->send();

        } catch (\Exception $e) {
            // Ensure we bring the site back up
            Artisan::call('up');

            $this->commandOutput .= "\n✗ UPDATE FAILED: " . $e->getMessage() . "\n";
            $this->commandOutput .= "Maintenance mode has been disabled.\n";

            Notification::make()
                ->title('Update Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        $this->isUpdating = false;
    }

    /**
     * Git Pull only (original method, enhanced)
     */
    public function gitPull(): void
    {
        $this->commandOutput = "Running git pull...\n";

        try {
            $homeDir = $this->getHomeDirectory();
            $branch = $this->versionInfo['branch'] ?? 'main';

            $process = new SymfonyProcess(['git', '-c', 'safe.directory=*', 'pull', 'origin', $branch]);
            $process->setWorkingDirectory(base_path());
            $process->setEnv([
                'HOME' => $homeDir,
                'COMPOSER_HOME' => "$homeDir/.composer",
                'GIT_SSH_COMMAND' => 'ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no',
            ]);
            $process->setTimeout(300);
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
                    $this->commandOutput .= "\n[WARN] composer.lock changed! Click 'Composer Install' to update dependencies.";
                }

                // Check for package.json changes
                if (str_contains($output, 'package.json') || str_contains($output, 'package-lock.json')) {
                    $this->commandOutput .= "\n[WARN] package.json changed! Click 'NPM Build' to rebuild assets.";
                }

                // Check for migrations
                if (str_contains($output, 'database/migrations')) {
                    $this->commandOutput .= "\n[INFO] New migrations detected. Click 'Migrate Database'!";
                }

                // Reload version info
                $this->loadVersionInfo();

            } else {
                $this->commandOutput .= "EXIT CODE: " . $process->getExitCode() . "\n";
                $this->commandOutput .= "ERROR:\n" . $process->getErrorOutput() . "\n";
                $this->commandOutput .= "OUTPUT:\n" . $process->getOutput();

                Notification::make()
                    ->title('Update Failed')
                    ->body('Git pull failed. Check the output log.')
                    ->danger()
                    ->send();

                // Add Diagnostics
                $this->commandOutput .= "\n--- DIAGNOSTICS ---\n";
                $this->commandOutput .= "User: " . trim(shell_exec('whoami')) . "\n";
                $this->commandOutput .= "Home: " . $homeDir . "\n";
                $this->commandOutput .= "UID: " . trim(shell_exec('id -u')) . "\n";
                $this->commandOutput .= "Remote: " . trim(shell_exec('cd ' . base_path() . ' && git remote -v')) . "\n";

                if (file_exists($homeDir . '/.ssh')) {
                    $this->commandOutput .= ".ssh dir: EXISTS\n";

                    // Check if key exists, if not generate it
                    $keyPath = $homeDir . '/.ssh/id_ed25519';
                    if (!file_exists($keyPath)) {
                        $this->commandOutput .= "No SSH Key found. Attempting to generate...\n";
                        // Generate key (no passphrase)
                        shell_exec("ssh-keygen -t ed25519 -N \"\" -f \"{$keyPath}\" 2>&1");
                    }

                    // Display Public Key
                    if (file_exists($keyPath . '.pub')) {
                        $pubKey = trim(file_get_contents($keyPath . '.pub'));
                        $this->commandOutput .= "\nIMPORTANT: Copy this key to GitHub > Settings > Deploy Keys:\n";
                        $this->commandOutput .= "-------------------------------------------------------\n";
                        $this->commandOutput .= $pubKey . "\n";
                        $this->commandOutput .= "-------------------------------------------------------\n";
                    } else {
                        $this->commandOutput .= "Error: Failed to generate/read public key.\n";
                        $this->commandOutput .= "Keys in dir: " . trim(shell_exec('ls -la ' . $homeDir . '/.ssh')) . "\n";
                    }
                } else {
                    $this->commandOutput .= ".ssh dir: NOT FOUND at " . $homeDir . "/.ssh\n";
                    $this->commandOutput .= "Attempting to create .ssh directory...\n";
                    mkdir($homeDir . '/.ssh', 0700, true);

                    // Try generation again
                    $keyPath = $homeDir . '/.ssh/id_ed25519';
                    shell_exec("ssh-keygen -t ed25519 -N \"\" -f \"{$keyPath}\" 2>&1");

                    if (file_exists($keyPath . '.pub')) {
                        $pubKey = trim(file_get_contents($keyPath . '.pub'));
                        $this->commandOutput .= "\nIMPORTANT: Copy this key to GitHub > Settings > Deploy Keys:\n";
                        $this->commandOutput .= "-------------------------------------------------------\n";
                        $this->commandOutput .= $pubKey . "\n";
                        $this->commandOutput .= "-------------------------------------------------------\n";
                    }
                }
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

    /**
     * Get home directory for shell commands
     */
    protected function getHomeDirectory(): string
    {
        $homeDir = $_SERVER['HOME'] ?? '';

        if (empty($homeDir)) {
            // For CyberPanel/cPanel: /home/username structure
            $basePath = base_path();
            if (preg_match('#^(/home/[^/]+)#', $basePath, $matches)) {
                $homeDir = $matches[1];
            } else {
                $homeDir = realpath(base_path('../../')) ?: '/tmp';
            }
        }

        return $homeDir;
    }

    /**
     * Check if app is in maintenance mode
     */
    public function isInMaintenanceMode(): bool
    {
        return app()->isDownForMaintenance();
    }
}
