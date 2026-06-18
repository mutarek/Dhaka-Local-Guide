@props(['title'])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    <h2 class="text-sm font-black uppercase tracking-wide text-slate-950 dark:text-white">{{ $title }}</h2>
    <div class="mt-4">
        {{ $slot }}
    </div>
</section>
