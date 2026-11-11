@props(['title' => null])
<section {{ $attributes->merge(['class' => 'rounded-lg border border-slate-200 bg-white shadow-sm']) }}>
    @if ($title || isset($actions))
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-3">
            <h2 class="font-semibold text-slate-900">{{ $title }}</h2>
            {{ $actions ?? '' }}
        </div>
    @endif
    {{ $slot }}
</section>
