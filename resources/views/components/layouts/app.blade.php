@props(['seo' => new \App\Support\SeoData(
    title: config('app.name'),
    description: config('app.name') . ' news and updates.',
)])
@php($canonical = $seo->canonical ?: url()->current())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo->title }}</title>
    <meta name="description" content="{{ $seo->description }}">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="{{ $seo->type }}">
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    @if ($seo->image)
        <meta property="og:image" content="{{ $seo->image }}">
        <meta property="og:image:alt" content="{{ $seo->title }}">
    @endif

    <meta name="twitter:card" content="{{ $seo->image ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seo->title }}">
    <meta name="twitter:description" content="{{ $seo->description }}">
    @if ($seo->image)
        <meta name="twitter:image" content="{{ $seo->image }}">
        <meta name="twitter:image:alt" content="{{ $seo->title }}">
    @endif

    @if ($seo->schema)
        <script type="application/ld+json">
            {!! json_encode($seo->schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif

    <script>
        document.documentElement.classList.toggle(
            'dark',
            localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
        );
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-950 antialiased transition-colors dark:bg-slate-950 dark:text-white">
    <x-site.header />

    <main class="pb-20 md:pb-0">
        {{ $slot }}
    </main>

    <x-site.footer />
    <x-site.mobile-bottom-nav />
</body>
</html>
