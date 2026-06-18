<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 px-3 py-2 shadow-2xl backdrop-blur md:hidden dark:border-slate-800 dark:bg-slate-950/95" aria-label="Mobile bottom navigation">
    <div class="mx-auto grid max-w-md grid-cols-4 gap-1 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300">
        <a href="{{ route('home') }}" class="rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-900">
            <svg class="mx-auto mb-1 h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M3 11.5 12 4l9 7.5V21h-6v-6H9v6H3v-9.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
            Home
        </a>
        <a href="{{ route('search', ['q' => 'Restaurants']) }}" class="rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-900">
            <svg class="mx-auto mb-1 h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Explore
        </a>
        <a href="{{ route('search', ['q' => 'Travel']) }}" class="rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-900">
            <svg class="mx-auto mb-1 h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 19 20 5M8 5h12v12M5 9l4 4M11 15l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Travel
        </a>
        <button data-theme-toggle type="button" class="rounded-xl px-2 py-2 transition hover:bg-slate-100 dark:hover:bg-slate-900">
            <svg class="mx-auto mb-1 h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 3v18m9-9A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Theme
        </button>
    </div>
</nav>
