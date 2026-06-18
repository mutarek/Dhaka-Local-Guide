@php
    $categoryCards = [
        ['name' => 'Restaurants', 'icon' => 'food'],
        ['name' => 'Bars', 'icon' => 'nightlife'],
        ['name' => 'Hospitals', 'icon' => 'health'],
        ['name' => 'Hotels', 'icon' => 'hotel'],
        ['name' => 'Tourist Spots', 'icon' => 'landmark'],
        ['name' => 'Shopping Malls', 'icon' => 'shopping'],
        ['name' => 'Bus Tickets', 'icon' => 'bus'],
        ['name' => 'Education', 'icon' => 'education'],
    ];

    $heroImage = $latestPosts->first()?->displayImageUrl() ?: 'https://images.unsplash.com/photo-1444723121867-7a241cacace9?auto=format&fit=crop&w=1600&q=80';
@endphp

<x-layouts.app :seo="$seo">
    <section class="relative isolate overflow-hidden bg-slate-950">
        <img src="{{ $heroImage }}" alt="Dhaka city guide" class="absolute inset-0 h-full w-full object-cover opacity-45" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/40 via-slate-950/65 to-slate-950"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8 lg:py-28">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-orange-400">Discover Dhaka Better</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-7xl">Discover the Best Places in Dhaka</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200 sm:text-lg">A premium city guide for restaurants, bars, hospitals, hotels, transport prices, travel ideas, and local discovery.</p>

                <x-site.search-bar class="mt-8 max-w-2xl" input-id="hero-search" placeholder="Search best restaurants, hospitals, hotels..." />

                <div class="mt-6 flex flex-wrap gap-2" aria-label="Popular categories">
                    @foreach (['Restaurants', 'Bars', 'Hospitals', 'Hotels', 'Travel', 'Bus Tickets'] as $chip)
                        <a href="{{ route('search', ['q' => $chip]) }}" class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-bold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white hover:text-slate-950">{{ $chip }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-6 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-ad-placement position="homepage_banner" />
        </div>
    </section>

    <section class="bg-white py-12 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-wide text-blue-600">Start exploring</p>
                    <h2 class="mt-1 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Browse by Category</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-600 dark:text-slate-300">Fast routes into the city topics people search for every day.</p>
            </div>

            <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($categoryCards as $categoryCard)
                    <x-site.category-card
                        :name="$categoryCard['name']"
                        :icon="$categoryCard['icon']"
                        :url="route('search', ['q' => $categoryCard['name']])"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-12 dark:bg-slate-900/60">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-black uppercase tracking-wide text-orange-500">Editor's picks</p>
                    <h2 class="mt-1 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Featured Articles</h2>
                </div>
                <a href="{{ route('search') }}" class="hidden rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5 hover:border-blue-600 hover:text-blue-600 sm:inline-flex dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">View all</a>
            </div>

            <div class="mt-7 grid gap-5 lg:grid-cols-3">
                @forelse ($latestPosts as $post)
                    @if ($loop->first)
                        <x-site.blog-card :post="$post" featured class="lg:col-span-2 lg:row-span-2" />
                    @else
                        <x-site.blog-card :post="$post" />
                    @endif
                @empty
                    @foreach (range(1, 3) as $item)
                        <div class="h-80 animate-pulse rounded-xl bg-slate-200 dark:bg-slate-800"></div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-white py-12 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-wide text-blue-600">Live pulse</p>
                    <h2 class="mt-1 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Trending Today</h2>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">Swipe horizontally on mobile.</p>
            </div>

            <div class="mt-7 flex snap-x gap-5 overflow-x-auto pb-4">
                @forelse ($popularPosts as $post)
                    <x-site.blog-card :post="$post" class="min-w-[82%] snap-start sm:min-w-[23rem]" />
                @empty
                    @foreach (range(1, 4) as $item)
                        <div class="min-w-[82%] animate-pulse rounded-xl bg-slate-200 sm:min-w-[23rem] dark:bg-slate-800"></div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-12 dark:bg-slate-900/60">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-wide text-orange-500">Most useful</p>
                    <h2 class="mt-1 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Popular This Week</h2>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">Trusted Dhaka guides worth saving.</p>
            </div>

            <div class="mt-7 grid gap-5 md:grid-cols-2">
                @forelse ($popularPosts as $post)
                    <x-site.blog-card :post="$post" horizontal />
                @empty
                    <p class="rounded-xl border border-slate-200 bg-white p-5 text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">No popular posts are available yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
