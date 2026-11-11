@php $baru = ! $kendaraan->exists; @endphp
<x-app-layout :title="$baru ? 'Tambah Kendaraan' : 'Ubah Kendaraan '.$kendaraan->nomor_polisi">
    <form method="POST" action="{{ $baru ? route('kendaraan.store') : route('kendaraan.update', $kendaraan) }}" class="max-w-4xl">
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <x-card title="Identitas Kendaraan">
            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <x-field name="nomor_polisi" label="Nomor polisi" required hint="Format: B 1234 PQA">
                    <input id="nomor_polisi" name="nomor_polisi" value="{{ old('nomor_polisi', $kendaraan->nomor_polisi) }}" class="input uppercase" maxlength="12">
                </x-field>
                <x-field name="jenis" label="Jenis" required>
                    <select id="jenis" name="jenis" class="input">
                        @foreach ($jenis as $j)<option @selected(old('jenis', $kendaraan->jenis) === $j)>{{ $j }}</option>@endforeach
                    </select>
                </x-field>
                <x-field name="merek" label="Merek" required>
                    <input id="merek" name="merek" value="{{ old('merek', $kendaraan->merek) }}" class="input" list="daftar-merek">
                    <datalist id="daftar-merek">
                        @foreach (['Toyota', 'Honda', 'Mitsubishi', 'Suzuki', 'Daihatsu', 'Isuzu', 'Hino', 'Yamaha', 'Nissan', 'Wuling'] as $m)<option value="{{ $m }}">@endforeach
                    </datalist>
                </x-field>
                <x-field name="tipe" label="Tipe / model" required>
                    <input id="tipe" name="tipe" value="{{ old('tipe', $kendaraan->tipe) }}" class="input" placeholder="mis. Kijang Innova 2.0 G">
                </x-field>
                <x-field name="tahun_produksi" label="Tahun produksi" required>
                    <input id="tahun_produksi" name="tahun_produksi" type="number" min="1980" max="{{ now()->year + 1 }}" value="{{ old('tahun_produksi', $kendaraan->tahun_produksi) }}" class="input">
                </x-field>
                <x-field name="warna" label="Warna" required>
                    <input id="warna" name="warna" value="{{ old('warna', $kendaraan->warna) }}" class="input">
                </x-field>
                <x-field name="nomor_rangka" label="Nomor rangka">
                    <input id="nomor_rangka" name="nomor_rangka" value="{{ old('nomor_rangka', $kendaraan->nomor_rangka) }}" class="input uppercase">
                </x-field>
                <x-field name="nomor_mesin" label="Nomor mesin">
                    <input id="nomor_mesin" name="nomor_mesin" value="{{ old('nomor_mesin', $kendaraan->nomor_mesin) }}" class="input uppercase">
                </x-field>
                <x-field name="kondisi" label="Kondisi" required class="sm:col-span-2">
                    <div class="flex flex-wrap gap-4 pt-1">
                        @foreach ($kondisi as $k)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="kondisi" value="{{ $k }}" @checked(old('kondisi', $kendaraan->kondisi) === $k)> {{ $k }}
                            </label>
                        @endforeach
                    </div>
                </x-field>
            </div>
        </x-card>

        <x-card title="Kepemilikan & Penanggung Jawab" class="mt-6">
            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <x-field name="perangkat_daerah_id" label="Perangkat daerah pemilik" required>
                    <select id="perangkat_daerah_id" name="perangkat_daerah_id" class="input">
                        <option value="">— Pilih perangkat daerah —</option>
                        @foreach ($daftarPd as $id => $nama)
                            <option value="{{ $id }}" @selected((string) old('perangkat_daerah_id', $kendaraan->perangkat_daerah_id) === (string) $id)>{{ $nama }}</option>
                        @endforeach
                    </select>
                </x-field>
                <x-field name="pegawai_id" label="Pegawai penanggung jawab" hint="Hanya pegawai dari perangkat daerah yang dipilih.">
                    <select id="pegawai_id" name="pegawai_id" class="input">
                        <option value="">— Belum ditetapkan —</option>
                        @foreach ($daftarPegawai as $p)
                            <option value="{{ $p->id }}" data-pd="{{ $p->perangkat_daerah_id }}" @selected((string) old('pegawai_id', $kendaraan->pegawai_id) === (string) $p->id)>{{ $p->nama }} — {{ $p->nip }}</option>
                        @endforeach
                    </select>
                </x-field>
                <x-field name="keterangan" label="Keterangan" class="sm:col-span-2">
                    <textarea id="keterangan" name="keterangan" rows="3" class="input">{{ old('keterangan', $kendaraan->keterangan) }}</textarea>
                </x-field>
            </div>
        </x-card>

        <div class="mt-6 flex gap-2">
            <button class="btn-primary">Simpan</button>
            <a href="{{ $baru ? route('kendaraan.index') : route('kendaraan.show', $kendaraan) }}" class="btn-secondary">Batal</a>
        </div>
    </form>

    <script>
        // Saring pilihan penanggung jawab sesuai perangkat daerah yang dipilih.
        (() => {
            const pd = document.getElementById('perangkat_daerah_id');
            const pegawai = document.getElementById('pegawai_id');
            const saring = () => {
                [...pegawai.options].forEach(o => {
                    if (!o.value) return;
                    o.hidden = o.disabled = o.dataset.pd !== pd.value;
                });
                if (pegawai.selectedOptions[0]?.disabled) pegawai.value = '';
            };
            pd.addEventListener('change', saring);
            saring();
        })();
    </script>
</x-app-layout>
