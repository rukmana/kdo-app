<x-app-layout title="Dashboard">
    @php
        $total = max($stat['total_kendaraan'], 1);
        $warnaKondisi = ['Baik' => 'bg-emerald-500', 'Rusak Ringan' => 'bg-amber-500', 'Rusak Berat' => 'bg-red-500'];
    @endphp

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ([
            ['Total Kendaraan', $stat['total_kendaraan'], route('kendaraan.index')],
            ['Pegawai (PNS)', $stat['total_pegawai'], route('pegawai.index')],
            ['Perangkat Daerah', $stat['total_perangkat_daerah'], route('perangkat-daerah.index')],
            ['Belum Ada Penanggung Jawab', $stat['tanpa_penanggung_jawab'], route('kendaraan.index')],
        ] as [$label, $nilai, $url])
            <a href="{{ $url }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-blue-300">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-1 text-3xl font-semibold text-slate-900 tabular-nums">{{ $nilai }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <x-card title="Kondisi Kendaraan" class="lg:col-span-1">
            <div class="space-y-4 p-5">
                <div class="flex h-3 overflow-hidden rounded-full bg-slate-100">
                    @foreach ($stat['per_kondisi'] as $kondisi => $jumlah)
                        <div class="{{ $warnaKondisi[$kondisi] }}" style="width: {{ $jumlah / $total * 100 }}%"></div>
                    @endforeach
                </div>
                <ul class="space-y-2 text-sm">
                    @foreach ($stat['per_kondisi'] as $kondisi => $jumlah)
                        <li class="flex items-center justify-between">
                            <a href="{{ route('kendaraan.index', ['kondisi' => $kondisi]) }}" class="flex items-center gap-2 hover:underline">
                                <span class="size-2.5 rounded-full {{ $warnaKondisi[$kondisi] }}"></span>{{ $kondisi }}
                            </a>
                            <span class="tabular-nums text-slate-600">{{ $jumlah }} <span class="text-slate-400">({{ round($jumlah / $total * 100) }}%)</span></span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t border-slate-100 pt-4">
                    <p class="mb-2 text-xs font-medium tracking-wide text-slate-500 uppercase">Per jenis</p>
                    <ul class="space-y-1 text-sm">
                        @foreach ($stat['per_jenis'] as $jenis => $jumlah)
                            <li class="flex justify-between"><span>{{ $jenis }}</span><span class="tabular-nums text-slate-600">{{ $jumlah }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </x-card>

        <x-card title="Kendaraan per Perangkat Daerah" class="lg:col-span-2">
            <div class="overflow-x-auto">
                <table class="tabel">
                    <thead><tr><th>Perangkat Daerah</th><th class="text-right">Baik</th><th class="text-right">Rusak Ringan</th><th class="text-right">Rusak Berat</th><th class="text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($stat['per_perangkat_daerah'] as $pd)
                            <tr>
                                <td><a class="link" href="{{ route('perangkat-daerah.show', $pd['id']) }}">{{ $pd['nama'] }}</a></td>
                                <td class="text-right tabular-nums">{{ $pd['baik'] }}</td>
                                <td class="text-right tabular-nums">{{ $pd['rusak_ringan'] }}</td>
                                <td class="text-right tabular-nums">{{ $pd['rusak_berat'] }}</td>
                                <td class="text-right font-semibold tabular-nums">{{ $pd['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <x-card title="Perlu Perhatian (Rusak)" class="mt-6">
        <x-slot:actions>
            <a href="{{ route('kendaraan.index', ['kondisi' => 'Rusak Berat']) }}" class="link text-sm">Lihat semua rusak berat →</a>
        </x-slot:actions>
        <div class="overflow-x-auto">
            <table class="tabel">
                <thead><tr><th>No. Polisi</th><th>Kendaraan</th><th>Perangkat Daerah</th><th>Kondisi</th><th>Keterangan</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($perluPerhatian as $k)
                        <tr>
                            <td class="whitespace-nowrap"><a class="link" href="{{ route('kendaraan.show', $k) }}">{{ $k->nomor_polisi }}</a></td>
                            <td>{{ $k->merek }} {{ $k->tipe }} ({{ $k->tahun_produksi }})</td>
                            <td>{{ $k->perangkatDaerah->singkatan ?? $k->perangkatDaerah->nama }}</td>
                            <td><x-kondisi-badge :kondisi="$k->kondisi" /></td>
                            <td class="text-slate-500">{{ $k->keterangan ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-slate-500">Semua kendaraan dalam kondisi baik.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
