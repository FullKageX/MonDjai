@php
    $admin = filament()->auth()->user();
@endphp

@if ($admin)
    <div class="fi-sidebar-group border-t border-gray-200 px-4 py-4 dark:border-white/10">
        <a href="{{ filament()->getProfileUrl() }}" class="flex items-center gap-x-3 rounded-lg p-2 transition hover:bg-gray-100 dark:hover:bg-white/5">
            @if ($admin->getFilamentAvatarUrl())
                <img
                    src="{{ $admin->getFilamentAvatarUrl() }}"
                    alt="Avatar de {{ $admin->getFilamentName() }}"
                    class="h-10 w-10 rounded-full object-cover ring-1 ring-gray-950/10 dark:ring-white/20"
                >
            @else
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-500 text-sm font-semibold text-white">
                    {{ str($admin->getFilamentName())->substr(0, 1)->upper() }}
                </span>
            @endif
            <span class="min-w-0">
                <span class="block text-xs text-gray-500 dark:text-gray-400">Bonjour</span>
                <span class="block truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $admin->getFilamentName() }}</span>
            </span>
        </a>

        <form method="POST" action="{{ filament()->getLogoutUrl() }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full rounded-lg px-2 py-1.5 text-left text-sm text-gray-600 transition hover:bg-gray-100 hover:text-gray-950 dark:text-gray-300 dark:hover:bg-white/5 dark:hover:text-white">
                Déconnexion
            </button>
        </form>
    </div>
@endif
