@php
    $shareUrl = urlencode($post->url());
    $shareTitle = urlencode($post->title);
@endphp

<x-layouts.app :seo="$seo">
    <article>
        <header class="bg-white dark:bg-slate-950">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <x-site.breadcrumb :items="[
                    ['label' => 'Home', 'url' => route('home')],
                    ['label' => $post->category->name, 'url' => route('categories.show', $post->category)],
                    ['label' => $post->title, 'url' => $post->url()],
                ]" />

                <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_0.55fr] lg:items-end">
                    <div>
                        <a href="{{ route('categories.show', $post->category) }}" class="inline-flex rounded-full bg-blue-600 px-4 py-2 text-xs font-black uppercase tracking-wide text-white shadow-sm">{{ $post->category->name }}</a>
                        <h1 class="mt-5 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-6xl dark:text-white">{{ $post->title }}</h1>
                        <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600 dark:text-slate-300">{{ $post->excerpt }}</p>

                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm font-semibold text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-2">
                                <span class="grid h-9 w-9 place-items-center rounded-xl bg-orange-100 text-xs font-black text-orange-600 dark:bg-orange-500/10">DC</span>
                                Dhaka City Desk
                            </span>
                            <span>{{ $post->readingTime() }} min read</span>
                            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:justify-end" aria-label="Share article">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-600 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-600 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">X</a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" target="_blank" rel="noopener" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-600 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">LinkedIn</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <img
                class="aspect-[16/10] w-full rounded-xl object-cover shadow-xl sm:aspect-[16/7]"
                src="{{ $post->displayImageUrl() }}"
                alt="{{ $post->title }}"
                fetchpriority="high"
            >
        </div>

        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_22rem] lg:px-8">
            <div class="min-w-0">
                <x-site.sidebar-widget title="Table of Contents" class="mb-8 lg:hidden">
                    <ol class="grid gap-3 text-sm">
                        @foreach ($tableOfContents as $item)
                            <li class="{{ $item['level'] === 3 ? 'pl-4' : '' }}">
                                <a href="#{{ $item['id'] }}" class="font-semibold text-slate-600 transition hover:text-blue-600 dark:text-slate-300">{{ $item['title'] }}</a>
                            </li>
                        @endforeach
                    </ol>
                </x-site.sidebar-widget>

                <div id="overview" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                    <x-ad-placement position="before_content" :post="$post" />

                    <div class="article-content space-y-6 text-lg leading-8 text-slate-700 dark:text-slate-200 [&_a]:font-semibold [&_a]:text-blue-600 [&_h2]:pt-4 [&_h2]:text-3xl [&_h2]:font-black [&_h2]:tracking-tight [&_h2]:text-slate-950 [&_h2]:dark:text-white [&_h3]:pt-3 [&_h3]:text-2xl [&_h3]:font-black [&_h3]:text-slate-950 [&_h3]:dark:text-white [&_p]:leading-8 [&_ul]:list-disc [&_ul]:pl-6">
                        {!! $content !!}
                    </div>

                    <x-ad-placement position="after_content" :post="$post" />
                </div>

                @if ($post->hasFaqs())
                    <section id="faqs" class="mt-8 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-2xl font-black tracking-tight text-slate-950 dark:text-white">Frequently Asked Questions</h2>
                        <div class="mt-5 divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($post->faqs as $faq)
                                @if (filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
                                    <details class="group py-5">
                                        <summary class="cursor-pointer text-base font-black text-slate-950 dark:text-white">{{ $faq['question'] }}</summary>
                                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $faq['answer'] }}</p>
                                    </details>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
                <x-site.sidebar-widget title="Table of Contents" class="hidden lg:block">
                    <ol class="grid gap-3 text-sm">
                        @foreach ($tableOfContents as $item)
                            <li class="{{ $item['level'] === 3 ? 'pl-4' : '' }}">
                                <a href="#{{ $item['id'] }}" class="font-semibold text-slate-600 transition hover:text-blue-600 dark:text-slate-300">{{ $item['title'] }}</a>
                            </li>
                        @endforeach
                    </ol>
                </x-site.sidebar-widget>

                <x-site.sidebar-widget title="Popular Articles">
                    <div class="grid gap-4">
                        @foreach ($popularArticles as $popularArticle)
                            <a href="{{ $popularArticle->url() }}" class="group block">
                                <p class="text-xs font-bold uppercase text-blue-600">{{ $popularArticle->category->name }}</p>
                                <h3 class="mt-1 text-sm font-black leading-5 text-slate-950 transition group-hover:text-blue-600 dark:text-white">{{ $popularArticle->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                </x-site.sidebar-widget>

                <x-ad-placement position="sidebar" :post="$post" />

                <x-site.sidebar-widget title="Categories">
                    <div class="grid gap-2">
                        @foreach ($categories as $category)
                            <a href="{{ route('categories.show', $category) }}" class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 text-sm font-bold text-slate-700 transition hover:bg-blue-50 hover:text-blue-600 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-800">
                                <span>{{ $category->name }}</span>
                                <span>{{ $category->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </x-site.sidebar-widget>

                <x-site.newsletter-card />
            </aside>
        </div>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="bg-white py-12 dark:bg-slate-950">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white">Related Articles</h2>
                <div class="mt-7 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedPosts as $relatedPost)
                        <x-site.blog-card :post="$relatedPost" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-ad-placement position="mobile_sticky_bottom" :post="$post" />
</x-layouts.app>
