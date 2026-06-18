@props([
    'name',
    'url',
    'count' => null,
    'icon' => 'map',
])

@php
    $icons = [
        'food' => 'M4 3v8a4 4 0 0 0 4 4v6m8-18v18m-4-18v5a4 4 0 0 0 8 0V3',
        'nightlife' => 'M8 3h8l-1 8a5 5 0 0 1-6 0L8 3Zm4 10v8m-4 0h8',
        'health' => 'M12 21s-7-4.35-9.33-9.8A5.5 5.5 0 0 1 12 5.1a5.5 5.5 0 0 1 9.33 6.1C19 16.65 12 21 12 21Zm0-12v6m-3-3h6',
        'hotel' => 'M4 21V7a2 2 0 0 1 2-2h7a3 3 0 0 1 3 3v13M4 13h16a2 2 0 0 1 2 2v6M8 9h.01M12 9h.01',
        'landmark' => 'M3 21h18M5 21V9m14 12V9M4 9l8-6 8 6M8 12h.01M12 12h.01M16 12h.01M8 16h.01M12 16h.01M16 16h.01',
        'shopping' => 'M6 8h12l-1 13H7L6 8Zm3 0a3 3 0 0 1 6 0',
        'bus' => 'M6 4h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2v2h-2v-2H8v2H6v-2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm0 8h12M8 16h.01M16 16h.01',
        'education' => 'M3 8l9-4 9 4-9 4-9-4Zm4 3v5c2 2 8 2 10 0v-5',
        'map' => 'M9 18 3 21V6l6-3 6 3 6-3v15l-6 3-6-3Zm0 0V3m6 18V6',
    ];
@endphp

<a href="{{ $url }}" {{ $attributes->merge(['class' => 'group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-600 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900']) }}>
    <div class="grid h-12 w-12 place-items-center rounded-xl bg-orange-100 text-orange-600 transition group-hover:scale-110 dark:bg-orange-500/10">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="{{ $icons[$icon] ?? $icons['map'] }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="mt-5 flex items-end justify-between gap-4">
        <h3 class="text-base font-black text-slate-950 dark:text-white">{{ $name }}</h3>
        @if (! is_null($count))
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $count }}</span>
        @endif
    </div>
</a>
