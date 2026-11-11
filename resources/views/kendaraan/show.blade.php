<x-app-layout :title="'Kendaraan '.$kendaraan->nomor_polisi">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <a href="{{ route('kendaraan.index') }}" class="link text-sm">← Daftar kendaraan</a>
        <div class="flex gap-2">
            <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="btn-primary">Ubah</a>
            <x-delete-button :action="route('kendaraan.destroy', $kendaraan)" label="Hapus" class="btn-danger" :confirm="'Hapus kendaraan '.$kendaraan->nomor_polisi.'?'" />
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <x-card title="Detail Kendaraan" class="lg:col-span-2">
            <dl class="detail">
                <div><dt>Nomor polisi</dt><dd class="text-lg font-semibold">{{ $kendaraan->nomor_polisi }}</dd></div>
                <div><dt>Kondisi</dt><dd><x-kondisi-badge :kondisi="$kendaraan->kondisi" /></dd></div>
                <div><dt>Merek</dt><dd>{{ $kendaraan->merek }}</dd></div>
                <div><dt>Tipe / model</dt><dd>{{ $kendaraan->tipe }}</dd></div>
                <div><dt>Jenis</dt><dd>{{ $kendaraan->jenis }}</dd></div>
                <div><dt>Tahun produksi</dt><dd>{{ $kendaraan->tahun_produksi }} <span class="text-slate-500">({{ now()->year - $kendaraan->tahun_produksi }} tahun)</span></dd></div>
                <div><dt>Warna</dt><dd>{{ $kendaraan->warna }}</dd></div>
                <div><dt>Nomor rangka</dt><dd class="font-mono">{{ $kendaraan->nomor_rangka ?? '—' }}</dd></div>
                <div><dt>Nomor mesin</dt><dd class="font-mono">{{ $kendaraan->nomor_mesin ?? '—' }}</dd></div>
                <div><dt>Terakhir diperbarui</dt><dd>{{ $kendaraan->updated_at->translatedFormat('d F Y H:i') }}</dd></div>
                <div class="sm:col-span-2"><dt>Keterangan</dt><dd>{{ $kendaraan->keterangan ?? '—' }}</dd></div>
            </dl>
        </x-card>

        <div class="space-y-6">
            <x-card title="Perangkat Daerah">
                <div class="p-5 text-sm">
                    <a class="link" href="{{ route('perangkat-daerah.show', $kendaraan->perangkatDaerah) }}">{{ $kendaraan->perangkatDaerah->nama }}</a>
                    <p class="text-slate-500">{{ $kendaraan->perangkatDaerah->alamat }}</p>
                </div>
            </x-card>
            <x-card title="Penanggung Jawab">
                <div class="p-5 text-sm">
                    @if ($kendaraan->pegawai)
                        <a class="link" href="{{ route('pegawai.show', $kendaraan->pegawai) }}">{{ $kendaraan->pegawai->nama }}</a>
                        <p class="font-mono text-slate-500">NIP {{ $kendaraan->pegawai->nip }}</p>
                        <p class="text-slate-500">{{ $kendaraan->pegawai->jabatan }} · {{ $kendaraan->pegawai->golongan }}</p>
                    @else
                        <p class="text-slate-500">Belum ditetapkan.</p>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    <x-card title="Riwayat Penanggung Jawab" class="mt-6">
        <div class="overflow-x-auto">
            <table class="tabel">
                <thead><tr><th>Pegawai</th><th>NIP</th><th>Mulai</th><th>Selesai</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($kendaraan->riwayatPenanggungJawab as $r)
                    <tr>
                        <td><a class="link" href="{{ route('pegawai.show', $r->pegawai) }}">{{ $r->pegawai->nama }}</a></td>
                        <td class="font-mono">{{ $r->pegawai->nip }}</td>
                        <td>{{ $r->tanggal_mulai->translatedFormat('d M Y') }}</td>
                        <td>{!! $r->tanggal_selesai ? e($r->tanggal_selesai->translatedFormat('d M Y')) : '<span class="text-emerald-700 font-medium">Aktif</span>' !!}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-slate-500">Belum ada riwayat.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
