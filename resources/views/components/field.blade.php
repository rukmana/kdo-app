@props(['name', 'label', 'required' => false, 'hint' => null])
<div {{ $attributes->merge(['class' => '']) }}>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-slate-700">
        {{ $label }} @if ($required)<span class="text-red-600">*</span>@endif
    </label>
    {{ $slot }}
    @if ($hint)<p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
