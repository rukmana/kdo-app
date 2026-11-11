<x-app-layout title="Kendaraan Dinas Operasional">
    <x-card>
        <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
            <div class="min-w-48 flex-1">
                <label class="mb-1 block text-xs font-medium text-slate-500" for="q">Cari</label>
                <input id="q" name="q" value="{{ $filter['q'] ?? '' }}" class="input" placeholder="Nomor polisi, merek, tipe…">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="kondisi">Kondisi</label>
                <select id="kondisi" name="kondisi" class="input">
                    <option value="">Semua</option>
                    @foreach (\App\Support\Referensi::KONDISI as $k)
                        <option @selected(($filter['kondisi'] ?? '') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="jenis">Jenis</label>
                <select id="jenis" name="jenis" class="input">
                    <option value="">Semua</option>
                    @foreach (\App\Support\Referensi::JENIS_KENDARAAN as $j)
                        <option @selected(($filter['jenis'] ?? '') === $j)>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" for="perangkat_daerah_id">Perangkat Daerah</label>
                <select id="perangkat_daerah_id" name="perangkat_daerah_id" class="input">
                    <option value="">Semua</option>
                    @foreach ($daftarPd as $id => $nama)
                        <option value="{{ $id }}" @selected((string) ($filter['perangkat_daerah_id'] ?? '') === (string) $id)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-secondary">Terapkan</button>
            @if (array_filter($filter))
                <a href="{{ route('kendaraan.index') }}" class="btn-secondary">Reset</a>
            @endif
            <div class="ml-auto flex gap-2">
                <a href="{{ route('kendaraan.export', request()->query()) }}" class="btn-secondary">Ekspor CSV</a>
                <a href="{{ route('kendaraan.create') }}" class="btn-primary">+ Tambah Kendaraan</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="tabel">
                <thead>
                <tr><th>No. Polisi</th><th>Merek / Tipe</th><th>Jenis</th><th>Tahun</th><th>Warna</th><th>Kondisi</th><th>Perangkat Daerah</th><th>Penanggung Jawab</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($kendaraan as $k)
                    <tr>
                        <td class="whitespace-nowrap font-medium"><a class="link" href="{{ route('kendaraan.show', $k) }}">{{ $k->nomor_polisi }}</a></td>
                        <td>{{ $k->merek }}<div class="text-xs text-slate-500">{{ $k->tipe }}</div></td>
                        <td class="whitespace-nowrap">{{ $k->jenis }}</td>
                        <td class="tabular-nums">{{ $k->tahun_produksi }}</td>
                        <td>{{ $k->warna }}</td>
                        <td><x-kondisi-badge :kondisi="$k->kondisi" /></td>
                        <td>{{ $k->perangkatDaerah->singkatan ?? $k->perangkatDaerah->nama }}</td>
                        <td>
                            @if ($k->pegawai)
                                <a class="link" href="{{ route('pegawai.show', $k->pegawai) }}">{{ $k->pegawai->nama }}</a>
                            @else
                                <span class="text-slate-400">Belum ditetapkan</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap text-right">
                            <a href="{{ route('kendaraan.edit', $k) }}" class="link mr-3">Ubah</a>
                            <x-delete-button :action="route('kendaraan.destroy', $k)" :confirm="'Hapus kendaraan '.$k->nomor_polisi.'?'" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-10 text-center text-slate-500">Tidak ada kendaraan yang cocok dengan filter.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 px-4 py-3 text-sm text-slate-500">
            <span>Menampilkan {{ $kendaraan->firstItem() ?? 0 }}–{{ $kendaraan->lastItem() ?? 0 }} dari {{ $kendaraan->total() }} kendaraan</span>
            {{ $kendaraan->links('pagination::simple-tailwind') }}
        </div>
    </x-card>
</x-app-layout>
