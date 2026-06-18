@php
    $navItems = [
        ['label' => 'Restaurants', 'url' => route('search', ['q' => 'Restaurants'])],
        ['label' => 'Hotels', 'url' => route('search', ['q' => 'Hotels'])],
        ['label' => 'Travel', 'url' => route('search', ['q' => 'Travel'])],
        ['label' => 'Hospitals', 'url' => route('search', ['q' => 'Hospitals'])],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl transition dark:border-slate-800 dark:bg-slate-950/90">
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3" aria-label="Dhaka City home">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-900 text-sm font-black text-white shadow-sm dark:bg-white dark:text-slate-950">DC</span>
            <span class="leading-tight">
                <span class="block text-base font-black tracking-tight text-slate-950 dark:text-white">Dhaka City</span>
                <span class="hidden text-xs font-medium text-slate-500 sm:block">Discover Dhaka Better</span>
            </span>
        </a>

        <div class="hidden flex-1 md:block">
            <x-site.search-bar compact input-id="header-search" placeholder="Search Dhaka guides..." />
        </div>

        <nav class="ml-auto hidden items-center gap-5 text-sm font-semibold text-slate-700 lg:flex dark:text-slate-200" aria-label="Primary navigation">
            @foreach ($navItems as $item)
                <a class="transition hover:text-blue-600 dark:hover:text-blue-400" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <button data-theme-toggle type="button" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-600 hover:text-blue-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200" aria-label="Toggle dark mode">
            <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36-6.36-1.42 1.42M7.06 16.94l-1.42 1.42m12.72 0-1.42-1.42M7.06 7.06 5.64 5.64M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M21 14.5A8.5 8.5 0 0 1 9.5 3 7 7 0 1 0 21 14.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
        </button>

        <details class="group lg:hidden">
            <summary class="grid h-10 w-10 cursor-pointer list-none place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200" aria-label="Open mobile menu">
                <svg class="h-5 w-5 group-open:hidden" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <svg class="hidden h-5 w-5 group-open:block" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </summary>
            <div class="absolute left-4 right-4 top-16 rounded-xl border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-800 dark:bg-slate-950">
                <x-site.search-bar compact input-id="mobile-menu-search" placeholder="Search Dhaka..." />
                <div class="mt-4 grid gap-2">
                    @foreach ($navItems as $item)
                        <a class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-900" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </details>
    </div>
</header>
