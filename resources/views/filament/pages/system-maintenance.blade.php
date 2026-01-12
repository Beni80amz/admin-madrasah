<x-filament-panels::page>
    <div class="space-y-8">

        {{-- System Info & Version - 2 Columns with smaller text --}}
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
            {{-- Version Info --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Informasi Versi</span>
                </x-slot>

                @if($versionInfo)
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; font-size: 0.75rem;">
                        <div>
                            <div style="color: #9ca3af;">Versi</div>
                            <div style="font-family: monospace; font-weight: 500; color: #10b981;">
                                {{ $versionInfo['current_version'] ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #9ca3af;">Branch</div>
                            <div style="font-weight: 500;">{{ $versionInfo['branch'] ?? '-' }}</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <div style="color: #9ca3af;">Commit</div>
                            <div style="font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Str::limit($versionInfo['last_commit_message'] ?? '-', 35) }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #9ca3af;">Tanggal</div>
                            <div>{{ $versionInfo['last_commit_date'] ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="color: #9ca3af;">Laravel</div>
                            <div>v{{ $versionInfo['laravel_version'] ?? '-' }}</div>
                        </div>
                    </div>
                @endif
            </x-filament::section>

            {{-- System Status --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Status Sistem</span>
                </x-slot>

                @if($systemInfo)
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; font-size: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                            @if($systemInfo['git']['available'] ?? false)
                                <span style="color: #22c55e;">✓</span>
                            @else
                                <span style="color: #ef4444;">✗</span>
                            @endif
                            <span>Git</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                            @if($systemInfo['composer']['available'] ?? false)
                                <span style="color: #22c55e;">✓</span>
                            @else
                                <span style="color: #ef4444;">✗</span>
                            @endif
                            <span>Composer</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                            @if($systemInfo['npm']['available'] ?? false)
                                <span style="color: #22c55e;">✓</span>
                            @else
                                <span style="color: #ef4444;">✗</span>
                            @endif
                            <span>NPM</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                            @if($systemInfo['node']['available'] ?? false)
                                <span style="color: #22c55e;">✓</span>
                            @else
                                <span style="color: #ef4444;">✗</span>
                            @endif
                            <span>Node</span>
                        </div>
                    </div>
                    <div
                        style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; font-size: 0.75rem; margin-top: 0.5rem;">
                        <div>
                            <div style="color: #9ca3af;">Disk</div>
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $systemInfo['disk_free'] ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #9ca3af;">Env</div>
                            <div
                                style="text-transform: uppercase; font-weight: 500; color: {{ ($systemInfo['app_env'] ?? '') === 'production' ? '#22c55e' : '#eab308' }};">
                                {{ $systemInfo['app_env'] ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <div style="color: #9ca3af;">Debug</div>
                            <div
                                style="color: {{ ($systemInfo['app_debug'] ?? '') === 'Enabled' ? '#ef4444' : '#22c55e' }};">
                                {{ $systemInfo['app_debug'] ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 0.5rem;">
                        <x-filament::button wire:click="loadSystemInfo" size="xs" color="gray">
                            Refresh
                        </x-filament::button>
                    </div>
                @endif
            </x-filament::section>
        </div>

        {{-- Main Actions - 3 Columns --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            {{-- Update Aplikasi --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Update Aplikasi</span>
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <x-filament::button wire:click="checkForUpdates" size="sm" color="info">
                            🔍 Cek Update
                        </x-filament::button>

                        @if($updateInfo && ($updateInfo['has_update'] ?? false))
                            <x-filament::button wire:click="performFullUpdate" size="sm" color="success"
                                :disabled="$isUpdating">
                                @if($isUpdating)
                                    ⏳ Updating...
                                @else
                                    🚀 Full Update
                                @endif
                            </x-filament::button>
                        @endif
                    </div>

                    @if($updateInfo)
                        <div
                            style="padding: 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; background: {{ $updateInfo['has_update'] ? 'rgba(234, 179, 8, 0.1)' : 'rgba(34, 197, 94, 0.1)' }};">
                            @if($updateInfo['has_update'])
                                <span style="color: #eab308;">⚠️ {{ $updateInfo['pending_count'] }} update</span>
                            @else
                                <span style="color: #22c55e;">✅ Up-to-date</span>
                            @endif
                        </div>
                    @endif
                </div>
            </x-filament::section>

            {{-- Update Manual --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Update Manual</span>
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <x-filament::button wire:click="gitPull" size="sm" color="primary" style="width: 100%;">
                        ⬇️ Git Pull
                    </x-filament::button>

                    <x-filament::button wire:click="hardResetGit" size="sm" color="danger" style="width: 100%;"
                        onclick="return confirm('PENTING: Ini akan menghapus semua perubahan manual di file sistem dan menyamakan 100% dengan GitHub. Lanjutkan?') || event.stopImmediatePropagation()">
                        ⚠️ Hard Reset Git
                    </x-filament::button>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;">
                        @if($systemInfo['composer']['available'] ?? false)
                            <x-filament::button wire:click="runComposerInstall" size="xs" color="gray">
                                📦 Composer
                            </x-filament::button>
                        @endif

                        @if($systemInfo['npm']['available'] ?? false)
                            <x-filament::button wire:click="runNpmBuild" size="xs" color="gray">
                                🧩 NPM
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </x-filament::section>

            {{-- Mode Maintenance --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Mode Maintenance</span>
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @if($this->isInMaintenanceMode())
                        <div
                            style="display: flex; align-items: center; gap: 0.25rem; padding: 0.5rem; background: rgba(234, 179, 8, 0.1); border-radius: 0.375rem; font-size: 0.75rem;">
                            <span>⚠️</span>
                            <span style="color: #eab308; font-weight: 500;">AKTIF</span>
                        </div>
                        <x-filament::button wire:click="toggleMaintenanceMode" size="sm" color="success"
                            style="width: 100%;">
                            ▶️ Nonaktifkan
                        </x-filament::button>
                    @else
                        <div
                            style="display: flex; align-items: center; gap: 0.25rem; padding: 0.5rem; background: rgba(34, 197, 94, 0.1); border-radius: 0.375rem; font-size: 0.75rem;">
                            <span>✅</span>
                            <span style="color: #22c55e; font-weight: 500;">Online</span>
                        </div>
                        <x-filament::button wire:click="toggleMaintenanceMode" size="sm" color="warning"
                            style="width: 100%;">
                            ⏸️ Aktifkan
                        </x-filament::button>
                    @endif
                </div>
            </x-filament::section>
        </div>

        {{-- Cache & Database - 3 Columns --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            {{-- Cache --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Cache</span>
                </x-slot>

                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <x-filament::button wire:click="clearCache" size="sm" color="warning">
                        🗑️ Clear
                    </x-filament::button>
                    <x-filament::button wire:click="optimizeApplication" size="sm" color="success">
                        🚀 Optimize
                    </x-filament::button>
                </div>
            </x-filament::section>

            {{-- Database --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Database</span>
                </x-slot>

                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <x-filament::button wire:click="migrateDatabase" size="sm" color="primary">
                        📤 Migrate
                    </x-filament::button>
                    <x-filament::button wire:click="linkStorage" size="sm" color="gray">
                        🔗 Storage
                    </x-filament::button>
                </div>
            </x-filament::section>

            {{-- Info --}}
            <x-filament::section compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Info</span>
                </x-slot>

                <div style="font-size: 0.75rem; color: #9ca3af;">
                    <p>PHP: {{ $versionInfo['php_version'] ?? PHP_VERSION }}</p>
                    <p>Check: {{ $updateInfo['last_check'] ?? 'Never' }}</p>
                </div>
            </x-filament::section>
        </div>

        {{-- Command Output --}}
        @if($commandOutput)
            <x-filament::section collapsible collapsed compact>
                <x-slot name="heading">
                    <span style="font-size: 0.875rem;">Output Log</span>
                </x-slot>

                <div
                    style="padding: 0.75rem; background: #1f2937; color: #f3f4f6; font-family: monospace; font-size: 0.75rem; border-radius: 0.375rem; overflow-x: auto; max-height: 15rem; overflow-y: auto;">
                    <pre style="white-space: pre-wrap; margin: 0;">{{ $commandOutput }}</pre>
                </div>
            </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>