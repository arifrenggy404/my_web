<x-filament-widgets::widget>
    <x-filament::section>
        <div class="font-mono text-xs space-y-2 select-none" style="color: #00f0ff;">
            <div class="flex items-center gap-2 border-b border-cyan-900 pb-1 mb-2">
                <span class="inline-block w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                <span class="font-bold uppercase tracking-wider">SYSTEM_MONITORING_UPLINK</span>
            </div>
            <div class="grid grid-cols-2 gap-y-1 gap-x-6">
                <div><span class="opacity-50">// HOST_OS:</span> <span class="text-white">{{ $os }}</span></div>
                <div><span class="opacity-50">// DATABASE_SIZE:</span> <span class="text-white">{{ $dbSize }}</span></div>
                <div><span class="opacity-50">// PHP_VERSION:</span> <span class="text-white">{{ $phpVersion }}</span></div>
                <div><span class="opacity-50">// TIMEZONE:</span> <span class="text-white">{{ $timezone }}</span></div>
                <div><span class="opacity-50">// LARAVEL_VER:</span> <span class="text-white">{{ $laravelVersion }}</span></div>
                <div><span class="opacity-50">// ENVIRONMENT:</span> <span class="text-white font-bold uppercase">{{ $environment }}</span></div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
