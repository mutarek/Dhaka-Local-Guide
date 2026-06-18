@php
    $footerCategories = \App\Models\Category::query()->active()->orderBy('sort_order')->take(6)->get();
@endphp

<footer class="border-t border-slate-200 bg-slate-950 text-white dark:border-slate-800">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <section>
            <h2 class="text-lg font-black">Dhaka City</h2>
            <p class="mt-3 text-sm leading-6 text-slate-300">A premium local discovery guide for restaurants, travel, health, hotels, transport, and everyday decisions across Dhaka.</p>
            <div class="mt-5 flex gap-3" aria-label="Social media">
                @foreach (['f', 'x', 'in'] as $social)
                    <a href="#" class="grid h-9 w-9 place-items-center rounded-xl bg-white/10 text-xs font-bold transition hover:-translate-y-0.5 hover:bg-blue-600">{{ $social }}</a>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-300">Categories</h2>
            <div class="mt-4 grid gap-2 text-sm">
                @forelse ($footerCategories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="text-slate-300 transition hover:text-white">{{ $category->name }}</a>
                @empty
                    <a href="{{ route('search', ['q' => 'Restaurants']) }}" class="text-slate-300 transition hover:text-white">Restaurants</a>
                    <a href="{{ route('search', ['q' => 'Travel']) }}" class="text-slate-300 transition hover:text-white">Travel</a>
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="text-sm font-bold uppercase tracking-wide text-slate-300">Quick Links</h2>
            <div class="mt-4 grid gap-2 text-sm">
                <a href="{{ route('home') }}" class="text-slate-300 transition hover:text-white">Home</a>
                <a href="{{ route('search') }}" class="text-slate-300 transition hover:text-white">Search</a>
                <a href="{{ route('sitemap') }}" class="text-slate-300 transition hover:text-white">Sitemap</a>
                <a href="{{ route('robots') }}" class="text-slate-300 transition hover:text-white">Robots</a>
            </div>
        </section>

        <section>
            <x-site.newsletter-card dark />
        </section>
    </div>
    <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-slate-400">
        &copy; {{ now()->year }} Dhaka City. Discover Dhaka Better.
    </div>
</footer>
