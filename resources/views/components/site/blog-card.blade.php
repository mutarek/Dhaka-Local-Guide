@props([
    'post',
    'featured' => false,
    'horizontal' => false,
])

<article {{ $attributes->merge(['class' => 'group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900']) }}>
    <a href="{{ $post->url() }}" class="{{ $horizontal ? 'grid gap-0 sm:grid-cols-[0.9fr_1.1fr]' : 'block' }}">
        <div class="relative overflow-hidden bg-slate-200">
            <img
                src="{{ $post->displayImageUrl() }}"
                alt="{{ $post->title }}"
                loading="lazy"
                class="{{ $featured ? 'h-72 sm:h-96' : ($horizontal ? 'h-56 sm:h-full' : 'h-56') }} w-full object-cover transition duration-500 group-hover:scale-105"
            >
            <div class="absolute left-4 top-4 rounded-full bg-blue-600 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white shadow-sm">{{ $post->category->name }}</div>
        </div>
        <div class="{{ $featured ? 'p-6 sm:p-7' : 'p-5' }}">
            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span>{{ $post->readingTime() }} min read</span>
                <span aria-hidden="true">•</span>
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                <span aria-hidden="true">•</span>
                <span>Dhaka City Desk</span>
            </div>
            <h3 class="{{ $featured ? 'mt-4 text-3xl' : 'mt-3 text-xl' }} font-black leading-tight tracking-tight text-slate-950 transition group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">{{ $post->title }}</h3>
            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $post->excerpt }}</p>
        </div>
    </a>
</article>
