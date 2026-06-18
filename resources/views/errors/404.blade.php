<x-layouts.app>
    <section class="bg-white py-20 dark:bg-slate-950">
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <p class="text-sm font-black uppercase tracking-wide text-blue-600">404</p>
            <h1 class="mt-3 text-4xl font-black tracking-tight text-slate-950 dark:text-white">Page not found</h1>
            <p class="mt-4 text-slate-600 dark:text-slate-300">The page may have moved, expired, or never existed.</p>
            <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-sm transition hover:bg-blue-700">Go home</a>
        </div>
    </section>
</x-layouts.app>
