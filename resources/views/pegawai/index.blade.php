<x-app-layout title="Pegawai Negeri Sipil">
    <x-card>
        <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
            <div class="min-w-48 flex-1">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="q">Cari</label>
                <input id="q" name="q" value="{{ request('q') }}" class="input" placeholder="Nama, NIP, jabatan…">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="perangkat_daerah_id">Perangkat Daerah</label>
                <select id="perangkat_daerah_id" name="perangkat_daerah_id" class="input">
                    <option value="">Semua</option>
                    @foreach ($daftarPd as $id => $nama)
                        <option value="{{ $id }}" @selected((string) request('perangkat_daerah_id') === (string) $id)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-secondary">Terapkan</button>
            <a href="{{ route('pegawai.create') }}" class="btn-primary ml-auto">+ Tambah Pegawai</a>
        </form>
        <div class="overflow-x-auto">
            <table class="tabel">
                <thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Gol.</th><th>Perangkat Daerah</th><th class="text-right">Kendaraan</th><th class="text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($pegawai as $p)
                    <tr>
                        <td class="font-medium"><a class="link" href="{{ route('pegawai.show', $p) }}">{{ $p->nama }}</a></td>
                        <td class="whitespace-nowrap font-mono text-xs">{{ $p->nip }}</td>
                        <td>{{ $p->jabatan }}</td>
                        <td class="whitespace-nowrap">{{ $p->golongan }}</td>
                        <td>{{ $p->perangkatDaerah->singkatan ?? $p->perangkatDaerah->nama }}</td>
                        <td class="text-right tabular-nums">{{ $p->kendaraan_count }}</td>
                        <td class="whitespace-nowrap text-right">
                            <a href="{{ route('pegawai.edit', $p) }}" class="link mr-3">Ubah</a>
                            <x-delete-button :action="route('pegawai.destroy', $p)" :confirm="'Hapus pegawai '.$p->nama.'?'" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-slate-500">Tidak ada data pegawai.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Menampilkan {{ $pegawai->firstItem() ?? 0 }}–{{ $pegawai->lastItem() ?? 0 }} dari {{ $pegawai->total() }} pegawai</span>
            {{ $pegawai->links('pagination::simple-tailwind') }}
        </div>
    </x-card>
</x-app-layout>
