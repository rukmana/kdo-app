@php $baru = ! $pegawai->exists; @endphp
<x-app-layout :title="$baru ? 'Tambah Pegawai' : 'Ubah Pegawai '.$pegawai->nama">
    <form method="POST" action="{{ $baru ? route('pegawai.store') : route('pegawai.update', $pegawai) }}" class="max-w-3xl">
        @csrf
        @unless ($baru) @method('PUT') @endunless
        <x-card title="Data Pegawai">
            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <x-field name="nama" label="Nama lengkap" required class="sm:col-span-2">
                    <input id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" class="input">
                </x-field>
                <x-field name="nip" label="NIP" required hint="18 digit angka; spasi boleh diketik.">
                    <input id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="input font-mono" inputmode="numeric" maxlength="21">
                </x-field>
                <x-field name="golongan" label="Golongan / ruang" required>
                    <select id="golongan" name="golongan" class="input">
                        <option value="">— Pilih —</option>
                        @foreach ($golongan as $g)<option @selected(old('golongan', $pegawai->golongan) === $g)>{{ $g }}</option>@endforeach
                    </select>
                </x-field>
                <x-field name="jabatan" label="Jabatan" required class="sm:col-span-2">
                    <input id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" class="input">
                </x-field>
                <x-field name="perangkat_daerah_id" label="Perangkat daerah" required class="sm:col-span-2">
                    <select id="perangkat_daerah_id" name="perangkat_daerah_id" class="input">
                        <option value="">— Pilih perangkat daerah —</option>
                        @foreach ($daftarPd as $id => $nama)
                            <option value="{{ $id }}" @selected((string) old('perangkat_daerah_id', $pegawai->perangkat_daerah_id) === (string) $id)>{{ $nama }}</option>
                        @endforeach
                    </select>
                </x-field>
                <x-field name="no_hp" label="Nomor HP">
                    <input id="no_hp" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" class="input" inputmode="tel">
                </x-field>
                <x-field name="email" label="Email">
                    <input id="email" name="email" type="email" value="{{ old('email', $pegawai->email) }}" class="input">
                </x-field>
            </div>
        </x-card>
        <div class="mt-6 flex gap-2">
            <button class="btn-primary">Simpan</button>
            <a href="{{ $baru ? route('pegawai.index') : route('pegawai.show', $pegawai) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</x-app-layout>
