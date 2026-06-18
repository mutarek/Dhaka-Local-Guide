@props([
    'position',
    'post' => null,
    'category' => null,
    'limit' => 1,
])

@php
    $ads = app(\App\Services\AdvertisementService::class)->getActiveAds(
        position: $position,
        post: $post,
        category: $category,
        limit: (int) $limit,
    );

    app(\App\Services\AdvertisementService::class)->recordImpressions($ads);
@endphp

@if ($ads->isNotEmpty())
    <div {{ $attributes->class([
        'ad-placement',
        'fixed inset-x-3 bottom-3 z-40 md:hidden' => $position === \App\Models\AdPackage::PLACEMENT_MOBILE_STICKY_BOTTOM,
        'my-6' => $position !== \App\Models\AdPackage::PLACEMENT_MOBILE_STICKY_BOTTOM,
    ]) }}>
        @foreach ($ads as $ad)
            <a
                href="{{ route('ads.click', $ad) }}"
                @if ($ad->open_in_new_tab) target="_blank" @endif
                rel="{{ $ad->relAttribute() }}"
                aria-label="{{ $ad->title }}"
                class="group relative block overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900"
            >
                @if ($ad->sponsored_label)
                    <span class="sr-only">Sponsored advertisement</span>
                    <span class="absolute m-3 rounded-full bg-slate-950/80 px-2.5 py-1 text-[0.65rem] font-black uppercase tracking-wide text-white backdrop-blur">Sponsored</span>
                @endif

                <picture>
                    <source media="(max-width: 767px)" srcset="{{ $ad->mobileImageUrl() }}">
                    <img
                        src="{{ $ad->imageUrl() }}"
                        alt="{{ $ad->title }}"
                        loading="lazy"
                        decoding="async"
                        class="h-auto w-full object-cover"
                    >
                </picture>
            </a>
        @endforeach
    </div>
@endif
