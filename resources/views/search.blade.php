<x-layouts.app :seo="$seo">
    <section class="bg-white py-10 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-site.breadcrumb :items="[
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Search', 'url' => route('search')],
            ]" />

            <div class="mt-8 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-wide text-blue-600">Explore Dhaka</p>
                <h1 class="mt-2 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl dark:text-white">Search Dhaka City</h1>
                <p class="mt-4 text-lg leading-8 text-slate-600 dark:text-slate-300">Find guides by title, category, tags, places, routes, and city topics.</p>
            </div>

            <x-site.search-bar class="mt-7 max-w-3xl" input-id="search-page-input" :query="$query" placeholder="Search restaurants, hospitals, bus tickets..." />
        </div>
    </section>

    <section class="bg-slate-50 py-12 dark:bg-slate-900/60">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($query !== '')
                <p class="mb-5 text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $posts->total() }} results for "{{ $query }}"</p>
            @endif

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <x-site.blog-card :post="$post" />
                @empty
                    <p class="rounded-xl border border-slate-200 bg-white p-5 text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">No guides matched your search.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
</x-layouts.app>
