@props([
    'query' => request('q', ''),
    'placeholder' => 'Search restaurants, hospitals, hotels...',
    'compact' => false,
    'inputId' => 'site-search',
])

@php($inputHeight = $compact ? 'min-h-11' : 'min-h-12')

<form action="{{ route('search') }}" method="GET" {{ $attributes->merge(['class' => 'relative']) }}>
    <label for="{{ $inputId }}" class="sr-only">Search Dhaka City</label>
    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 dark:border-slate-700 dark:bg-slate-900 dark:focus-within:ring-blue-950">
        <span class="flex items-center pl-4 text-slate-400">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </span>
        <input
            id="{{ $inputId }}"
            name="q"
            value="{{ $query }}"
            type="search"
            placeholder="{{ $placeholder }}"
            class="{{ $inputHeight }} w-full border-0 bg-transparent px-3 text-sm text-slate-900 outline-none placeholder:text-slate-400 dark:text-white"
        >
        <button class="bg-blue-600 px-4 text-sm font-semibold text-white transition hover:bg-blue-700 sm:px-5" type="submit">
            Search
        </button>
    </div>
</form>
