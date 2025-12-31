<x-filament-panels::page>
    <div class="space-y-6">

        {{-- System Info & Version --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Version Info --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-information-circle class="w-5 h-5" />
                        Informasi Versi
                    </div>
                </x-slot>

                @if($versionInfo)
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Versi Saat Ini</dt>
                            <dd class="font-mono font-medium text-primary-600 dark:text-primary-400">{{ $versionInfo['current_version'] ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Branch</dt>
                            <dd class="font-medium">{{ $versionInfo['branch'] ?? '-' }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-gray-500 dark:text-gray-400">Commit Terakhir</dt>
                            <dd class="font-medium truncate">{{ $versionInfo['last_commit_message'] ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Tanggal Commit</dt>
                            <dd class="text-xs">{{ $versionInfo['last_commit_date'] ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Laravel</dt>
                            <dd>v{{ $versionInfo['laravel_version'] ?? '-' }}</dd>
                        </div>
                    </dl>
                @endif
            </x-filament::section>

            {{-- System Requirements --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-server class="w-5 h-5" />
                        Status Sistem
                    </div>
                </x-slot>

                @if($systemInfo)
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2">
                            @if($systemInfo['git']['available'] ?? false)
                                <x-heroicon-o-check-circle class="w-4 h-4 text-success-500" />
                            @else
                                <x-heroicon-o-x-circle class="w-4 h-4 text-danger-500" />
                            @endif
                            <span>Git</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($systemInfo['composer']['available'] ?? false)
                                <x-heroicon-o-check-circle class="w-4 h-4 text-success-500" />
                            @else
                                <x-heroicon-o-x-circle class="w-4 h-4 text-danger-500" />
                            @endif
                            <span>Composer</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($systemInfo['npm']['available'] ?? false)
                                <x-heroicon-o-check-circle class="w-4 h-4 text-success-500" />
                            @else
                                <x-heroicon-o-x-circle class="w-4 h-4 text-danger-500" />
                            @endif
                            <span>NPM</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($systemInfo['node']['available'] ?? false)
                                <x-heroicon-o-check-circle class="w-4 h-4 text-success-500" />
                            @else
                                <x-heroicon-o-x-circle class="w-4 h-4 text-danger-500" />
                            @endif
                            <span>Node.js</span>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-gray-500 dark:text-gray-400">Disk Space</dt>
                            <dd class="text-xs">{{ $systemInfo['disk_free'] ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Environment</dt>
                            <dd class="uppercase text-xs font-medium {{ ($systemInfo['app_env'] ?? '') === 'production' ? 'text-success-600' : 'text-warning-600' }}">
                                {{ $systemInfo['app_env'] ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Debug Mode</dt>
                            <dd class="text-xs {{ ($systemInfo['app_debug'] ?? '') === 'Enabled' ? 'text-danger-600' : 'text-success-600' }}">
                                {{ $systemInfo['app_debug'] ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                    <div class="mt-3">
                        <x-filament::button wire:click="loadSystemInfo" size="xs" color="gray" icon="heroicon-o-arrow-path">
                            Refresh
                        </x-filament::button>
                    </div>
                @endif
            </x-filament::section>
        </div>

        {{-- Update Section --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-arrow-path class="w-5 h-5" />
                    Update Aplikasi
                </div>
            </x-slot>
            <x-slot name="description">
                Periksa dan terapkan update terbaru dari repository GitHub.
            </x-slot>

            <div class="space-y-4">
                {{-- Check Update Button --}}
                <div class="flex flex-wrap gap-3">
                    <x-filament::button wire:click="checkForUpdates" icon="heroicon-o-magnifying-glass" color="info">
                        Periksa Update
                    </x-filament::button>

                    @if($updateInfo && ($updateInfo['has_update'] ?? false))
                        <x-filament::button
                            wire:click="performFullUpdate"
                            icon="heroicon-o-rocket-launch"
                            color="success"
                            :disabled="$isUpdating"
                        >
                            @if($isUpdating)
                                <x-filament::loading-indicator class="w-4 h-4 mr-2" />
                                Sedang Update...
                            @else
                                Update Sekarang (Full)
                            @endif
                        </x-filament::button>
                    @endif
                </div>

                {{-- Update Info --}}
                @if($updateInfo)
                    <div class="p-4 rounded-lg {{ $updateInfo['has_update'] ? 'bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800' : 'bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800' }}">
                        @if($updateInfo['has_update'])
                            <div class="flex items-center gap-2 text-warning-700 dark:text-warning-400 font-medium">
                                <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                                <span>{{ $updateInfo['pending_count'] }} update tersedia!</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-mono">{{ $updateInfo['current_version'] }}</span>
                                <x-heroicon-o-arrow-right class="w-4 h-4 inline" />
                                <span class="font-mono text-success-600">{{ $updateInfo['latest_version'] }}</span>
                            </div>

                            @if(!empty($updateInfo['pending_updates']))
                                <div class="mt-3 max-h-40 overflow-y-auto">
                                    <p class="text-xs text-gray-500 mb-1">Perubahan yang akan diterapkan:</p>
                                    <ul class="space-y-1 text-xs font-mono text-gray-600 dark:text-gray-400">
                                        @foreach(array_slice($updateInfo['pending_updates'], 0, 10) as $update)
                                            <li>• {{ $update }}</li>
                                        @endforeach
                                        @if(count($updateInfo['pending_updates']) > 10)
                                            <li class="text-gray-400">... dan {{ count($updateInfo['pending_updates']) - 10 }} lainnya</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        @else
                            <div class="flex items-center gap-2 text-success-700 dark:text-success-400">
                                <x-heroicon-o-check-circle class="w-5 h-5" />
                                <span>Sistem sudah up-to-date!</span>
                            </div>
                        @endif
                        <p class="mt-2 text-xs text-gray-500">Terakhir diperiksa: {{ $updateInfo['last_check'] ?? '-' }}</p>
                    </div>
                @endif
            </div>
        </x-filament::section>

        {{-- Manual Update Options --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Git Only --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-code-bracket class="w-5 h-5" />
                        Update Manual (Git Only)
                    </div>
                </x-slot>
                <x-slot name="description">
                    Tarik perubahan terbaru tanpa install dependencies.
                </x-slot>

                <div class="space-y-3">
                    <x-filament::button wire:click="gitPull" icon="heroicon-o-arrow-down-tray" color="primary">
                        Git Pull
                    </x-filament::button>

                    <div class="flex flex-wrap gap-2">
                        @if($systemInfo['composer']['available'] ?? false)
                            <x-filament::button wire:click="runComposerInstall" size="sm" color="gray" icon="heroicon-o-cube">
                                Composer Install
                            </x-filament::button>
                        @endif

                        @if($systemInfo['npm']['available'] ?? false)
                            <x-filament::button wire:click="runNpmBuild" size="sm" color="gray" icon="heroicon-o-puzzle-piece">
                                NPM Build
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </x-filament::section>

            {{-- Maintenance Mode --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-wrench class="w-5 h-5" />
                        Mode Maintenance
                    </div>
                </x-slot>
                <x-slot name="description">
                    Aktifkan untuk mencegah akses user saat update.
                </x-slot>

                <div class="space-y-3">
                    @if($this->isInMaintenanceMode())
                        <div class="flex items-center gap-2 p-3 bg-warning-50 dark:bg-warning-900/20 rounded-lg border border-warning-200 dark:border-warning-800">
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-warning-500" />
                            <span class="text-warning-700 dark:text-warning-400 font-medium">Maintenance Mode AKTIF</span>
                        </div>
                        <x-filament::button wire:click="toggleMaintenanceMode" color="success" icon="heroicon-o-play">
                            Nonaktifkan Maintenance
                        </x-filament::button>
                    @else
                        <div class="flex items-center gap-2 p-3 bg-success-50 dark:bg-success-900/20 rounded-lg border border-success-200 dark:border-success-800">
                            <x-heroicon-o-check-circle class="w-5 h-5 text-success-500" />
                            <span class="text-success-700 dark:text-success-400 font-medium">Website Online</span>
                        </div>
                        <x-filament::button wire:click="toggleMaintenanceMode" color="warning" icon="heroicon-o-pause">
                            Aktifkan Maintenance Mode
                        </x-filament::button>
                    @endif
                </div>
            </x-filament::section>
        </div>

        {{-- Cache & Database --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Cache & Optimization --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5" />
                        Performance & Cache
                    </div>
                </x-slot>
                <x-slot name="description">
                    Bersihkan atau rebuild cache aplikasi.
                </x-slot>

                <div class="flex flex-wrap gap-3">
                    <x-filament::button wire:click="clearCache" color="warning" icon="heroicon-o-trash">
                        Bersihkan Cache
                    </x-filament::button>
                    <x-filament::button wire:click="optimizeApplication" color="success" icon="heroicon-o-rocket-launch">
                        Optimize
                    </x-filament::button>
                </div>
            </x-filament::section>

            {{-- Database --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-circle-stack class="w-5 h-5" />
                        Database & Storage
                    </div>
                </x-slot>
                <x-slot name="description">
                    Manajemen database dan symbolic link.
                </x-slot>

                <div class="flex flex-wrap gap-3">
                    <x-filament::button wire:click="migrateDatabase" color="primary" icon="heroicon-o-arrow-up-tray">
                        Migrate Database
                    </x-filament::button>
                    <x-filament::button wire:click="linkStorage" color="gray" icon="heroicon-o-link">
                        Link Storage
                    </x-filament::button>
                </div>
            </x-filament::section>
        </div>

        {{-- Command Output --}}
        @if($commandOutput)
            <x-filament::section collapsible>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-command-line class="w-5 h-5" />
                        Output Log
                    </div>
                </x-slot>

                <div class="p-4 bg-gray-900 text-gray-100 font-mono text-sm rounded-lg overflow-x-auto max-h-96 overflow-y-auto">
                    <pre class="whitespace-pre-wrap">{{ $commandOutput }}</pre>
                </div>
            </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>