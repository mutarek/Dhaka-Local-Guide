@props(['dark' => false])

<div {{ $attributes->merge(['class' => ($dark ? 'bg-white/10 text-white' : 'bg-slate-950 text-white').' rounded-xl p-5 shadow-sm']) }}>
    <h2 class="text-lg font-black">Get Dhaka picks weekly</h2>
    <p class="mt-2 text-sm leading-6 {{ $dark ? 'text-slate-300' : 'text-slate-200' }}">Restaurants, travel routes, healthcare guides, and city tips in one concise email.</p>
    <form class="mt-4 grid gap-3">
        <label class="sr-only" for="newsletter-email">Email address</label>
        <input id="newsletter-email" type="email" placeholder="you@example.com" class="min-h-11 rounded-xl border border-white/10 bg-white px-4 text-sm text-slate-950 outline-none ring-blue-200 transition focus:ring-4">
        <button type="submit" class="min-h-11 rounded-xl bg-orange-500 px-4 text-sm font-bold text-white transition hover:bg-orange-600">Subscribe</button>
    </form>
</div>
