@props(['title' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
@php
    $menu = [
        ['dashboard', 'Dashboard', 'dashboard'],
        ['kendaraan.index', 'Kendaraan Dinas', 'kendaraan.*'],
        ['pegawai.index', 'Pegawai (PNS)', 'pegawai.*'],
        ['perangkat-daerah.index', 'Perangkat Daerah', 'perangkat-daerah.*'],
    ];
@endphp
<div class="flex min-h-screen">
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 -translate-x-full bg-slate-900 text-slate-200 transition-transform lg:static lg:translate-x-0">
        <div class="flex h-16 items-center gap-3 border-b border-slate-800 px-5">
            <div class="grid size-9 place-items-center rounded-lg bg-red-600 font-bold text-white">K</div>
            <div>
                <p class="font-semibold leading-tight text-white">SIM KDO</p>
                <p class="text-xs text-slate-400">Barang Milik Daerah</p>
            </div>
        </div>
        <nav class="space-y-1 p-3">
            @foreach ($menu as [$route, $label, $pattern])
                <a href="{{ route($route) }}"
                   @class([
                       'block rounded-md px-3 py-2 text-sm font-medium',
                       'bg-slate-800 text-white' => request()->routeIs($pattern),
                       'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs($pattern),
                   ])>{{ $label }}</a>
            @endforeach
            <a href="{{ route('docs') }}" target="_blank" class="block rounded-md px-3 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white">Dokumentasi API ↗</a>
        </nav>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
            <button type="button" class="rounded-md border border-slate-300 px-2 py-1 text-sm lg:hidden"
                    onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">Menu</button>
            <h1 class="truncate text-lg font-semibold text-slate-900">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-3 text-sm">
                <span class="hidden text-slate-500 sm:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-md border border-slate-300 px-3 py-1.5 hover:bg-slate-50">Keluar</button>
                </form>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl flex-1 p-4 lg:p-8">
            @if (session('sukses'))
                <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('sukses') }}</div>
            @endif
            @if (session('gagal'))
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('gagal') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
