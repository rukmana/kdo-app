<x-app-layout title="Perangkat Daerah">
    <x-card>
        <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
            <div class="min-w-48 flex-1">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="q">Cari</label>
                <input id="q" name="q" value="{{ request('q') }}" class="input" placeholder="Nama, kode, singkatan…">
            </div>
            <button class="btn-secondary">Cari</button>
            <a href="{{ route('perangkat-daerah.create') }}" class="btn-primary ml-auto">+ Tambah Perangkat Daerah</a>
        </form>
        <div class="overflow-x-auto">
            <table class="tabel">
                <thead><tr><th>Kode</th><th>Nama</th><th>Singkatan</th><th>Telepon</th><th class="text-right">Pegawai</th><th class="text-right">Kendaraan</th><th class="text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($perangkatDaerah as $pd)
                    <tr>
                        <td class="font-mono text-xs">{{ $pd->kode }}</td>
                        <td class="font-medium"><a class="link" href="{{ route('perangkat-daerah.show', $pd) }}">{{ $pd->nama }}</a></td>
                        <td>{{ $pd->singkatan ?? '—' }}</td>
                        <td class="whitespace-nowrap">{{ $pd->telepon ?? '—' }}</td>
                        <td class="text-right tabular-nums">{{ $pd->pegawai_count }}</td>
                        <td class="text-right tabular-nums">{{ $pd->kendaraan_count }}</td>
                        <td class="whitespace-nowrap text-right">
                            <a href="{{ route('perangkat-daerah.edit', $pd) }}" class="link mr-3">Ubah</a>
                            <x-delete-button :action="route('perangkat-daerah.destroy', $pd)" :confirm="'Hapus '.$pd->nama.'?'" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-slate-500">Tidak ada data perangkat daerah.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Total {{ $perangkatDaerah->total() }} perangkat daerah</span>
            {{ $perangkatDaerah->links('pagination::simple-tailwind') }}
        </div>
    </x-card>
</x-app-layout>
