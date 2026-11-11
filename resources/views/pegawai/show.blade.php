<x-app-layout :title="$pegawai->nama">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <a href="{{ route('pegawai.index') }}" class="link text-sm">← Daftar pegawai</a>
        <div class="flex gap-2">
            <a href="{{ route('pegawai.edit', $pegawai) }}" class="btn-primary">Ubah</a>
            <x-delete-button :action="route('pegawai.destroy', $pegawai)" class="btn-danger" :confirm="'Hapus pegawai '.$pegawai->nama.'?'" />
        </div>
    </div>

    <x-card title="Detail Pegawai">
        <dl class="detail">
            <div><dt>Nama</dt><dd class="text-lg font-semibold">{{ $pegawai->nama }}</dd></div>
            <div><dt>NIP</dt><dd class="font-mono">{{ $pegawai->nip }}</dd></div>
            <div><dt>Jabatan</dt><dd>{{ $pegawai->jabatan }}</dd></div>
            <div><dt>Golongan / ruang</dt><dd>{{ $pegawai->golongan }}</dd></div>
            <div><dt>Perangkat daerah</dt><dd><a class="link" href="{{ route('perangkat-daerah.show', $pegawai->perangkatDaerah) }}">{{ $pegawai->perangkatDaerah->nama }}</a></dd></div>
            <div><dt>Kontak</dt><dd>{{ $pegawai->no_hp ?? '—' }}<br><span class="text-slate-500">{{ $pegawai->email }}</span></dd></div>
        </dl>
    </x-card>

    <x-card :title="'Kendaraan yang Dipertanggungjawabkan ('.$pegawai->kendaraan->count().')'" class="mt-6">
        <x-slot:actions>
            <a href="{{ route('kendaraan.create', ['perangkat_daerah_id' => $pegawai->perangkat_daerah_id]) }}" class="link text-sm">+ Tambah kendaraan</a>
        </x-slot:actions>
        <div class="overflow-x-auto">
            <table class="tabel">
                <thead><tr><th>No. Polisi</th><th>Kendaraan</th><th>Tahun</th><th>Kondisi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($pegawai->kendaraan as $k)
                    <tr>
                        <td><a class="link" href="{{ route('kendaraan.show', $k) }}">{{ $k->nomor_polisi }}</a></td>
                        <td>{{ $k->merek }} {{ $k->tipe }}</td>
                        <td>{{ $k->tahun_produksi }}</td>
                        <td><x-kondisi-badge :kondisi="$k->kondisi" /></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-slate-500">Tidak sedang bertanggung jawab atas kendaraan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <x-card title="Riwayat Penanggung Jawab" class="mt-6">
        <div class="overflow-x-auto">
            <table class="tabel">
                <thead><tr><th>Kendaraan</th><th>Mulai</th><th>Selesai</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($pegawai->riwayatPenanggungJawab as $r)
                    <tr>
                        <td><a class="link" href="{{ route('kendaraan.show', $r->kendaraan) }}">{{ $r->kendaraan->nomor_polisi }}</a> <span class="text-slate-500">{{ $r->kendaraan->merek }} {{ $r->kendaraan->tipe }}</span></td>
                        <td>{{ $r->tanggal_mulai->translatedFormat('d M Y') }}</td>
                        <td>{!! $r->tanggal_selesai ? e($r->tanggal_selesai->translatedFormat('d M Y')) : '<span class="text-emerald-700 font-medium">Aktif</span>' !!}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-slate-500">Belum ada riwayat.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
