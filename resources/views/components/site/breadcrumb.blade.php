@props(['items'])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'text-sm']) }}>
    <ol class="flex flex-wrap items-center gap-2 text-slate-500 dark:text-slate-400">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if (! $loop->first)
                    <span aria-hidden="true">/</span>
                @endif
                @if (! $loop->last)
                    <a href="{{ $item['url'] }}" class="font-semibold transition hover:text-blue-600 dark:hover:text-blue-400">{{ $item['label'] }}</a>
                @else
                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
