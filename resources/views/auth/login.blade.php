<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 px-4 text-slate-800 antialiased">
<div class="w-full max-w-sm">
    <div class="mb-6 text-center">
        <div class="mx-auto mb-3 grid size-12 place-items-center rounded-xl bg-red-600 text-xl font-bold text-white">K</div>
        <h1 class="text-xl font-semibold text-slate-900">SIM Kendaraan Dinas Operasional</h1>
        <p class="text-sm text-slate-500">Pengelolaan Barang Milik Daerah</p>
    </div>
    <form method="POST" action="{{ url('/login') }}" class="space-y-4 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <x-field name="email" label="Email" required>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="input" autofocus autocomplete="username">
        </x-field>
        <x-field name="password" label="Kata sandi" required>
            <input id="password" name="password" type="password" class="input" autocomplete="current-password">
        </x-field>
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="rounded border-slate-300"> Ingat saya
        </label>
        <button class="btn-primary w-full">Masuk</button>
    </form>
    <p class="mt-4 text-center text-xs text-slate-500">Akun demo dari seeder tercantum di README.</p>
</div>
</body>
</html>
