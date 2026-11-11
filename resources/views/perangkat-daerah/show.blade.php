<x-app-layout :title="$perangkatDaerah->nama">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <a href="{{ route('perangkat-daerah.index') }}" class="link text-sm">← Daftar perangkat daerah</a>
        <div class="flex gap-2">
            <a href="{{ route('perangkat-daerah.edit', $perangkatDaerah) }}" class="btn-primary">Ubah</a>
            <x-delete-button :action="route('perangkat-daerah.destroy', $perangkatDaerah)" class="btn-danger" :confirm="'Hapus '.$perangkatDaerah->nama.'?'" />
        </div>
    </div>

    <x-card title="Detail Perangkat Daerah">
        <dl class="detail">
            <div><dt>Nama</dt><dd class="text-lg font-semibold">{{ $perangkatDaerah->nama }}</dd></div>
            <div><dt>Kode / singkatan</dt><dd><span class="font-mono">{{ $perangkatDaerah->kode }}</span> · {{ $perangkatDaerah->singkatan ?? '—' }}</dd></div>
            <div><dt>Alamat</dt><dd>{{ $perangkatDaerah->alamat ?? '—' }}</dd></div>
            <div><dt>Telepon</dt><dd>{{ $perangkatDaerah->telepon ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-card :title="'Kendaraan ('.$perangkatDaerah->kendaraan->count().')'">
            <x-slot:actions>
                <a href="{{ route('kendaraan.create', ['perangkat_daerah_id' => $perangkatDaerah->id]) }}" class="link text-sm">+ Tambah</a>
            </x-slot:actions>
            <div class="overflow-x-auto">
                <table class="tabel">
                    <thead><tr><th>No. Polisi</th><th>Kendaraan</th><th>Kondisi</th><th>Penanggung Jawab</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse ($perangkatDaerah->kendaraan as $k)
                        <tr>
                            <td class="whitespace-nowrap"><a class="link" href="{{ route('kendaraan.show', $k) }}">{{ $k->nomor_polisi }}</a></td>
                            <td>{{ $k->merek }} {{ $k->tipe }}</td>
                            <td><x-kondisi-badge :kondisi="$k->kondisi" /></td>
                            <td>{{ $k->pegawai?->nama ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-slate-500">Belum ada kendaraan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
        <x-card :title="'Pegawai ('.$perangkatDaerah->pegawai->count().')'">
            <x-slot:actions>
                <a href="{{ route('pegawai.create', ['perangkat_daerah_id' => $perangkatDaerah->id]) }}" class="link text-sm">+ Tambah</a>
            </x-slot:actions>
            <div class="overflow-x-auto">
                <table class="tabel">
                    <thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th><th class="text-right">Kendaraan</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse ($perangkatDaerah->pegawai as $p)
                        <tr>
                            <td><a class="link" href="{{ route('pegawai.show', $p) }}">{{ $p->nama }}</a></td>
                            <td class="font-mono text-xs">{{ $p->nip }}</td>
                            <td>{{ $p->jabatan }}</td>
                            <td class="text-right tabular-nums">{{ $p->kendaraan_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-slate-500">Belum ada pegawai.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
