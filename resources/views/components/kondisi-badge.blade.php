@props(['kondisi'])
<span @class([
    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset whitespace-nowrap',
    'bg-emerald-50 text-emerald-700 ring-emerald-600/20' => $kondisi === 'Baik',
    'bg-amber-50 text-amber-800 ring-amber-600/20' => $kondisi === 'Rusak Ringan',
    'bg-red-50 text-red-700 ring-red-600/20' => $kondisi === 'Rusak Berat',
])>{{ $kondisi }}</span>
