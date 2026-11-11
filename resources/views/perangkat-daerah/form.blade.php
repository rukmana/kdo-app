@php $baru = ! $perangkatDaerah->exists; @endphp
<x-app-layout :title="$baru ? 'Tambah Perangkat Daerah' : 'Ubah '.$perangkatDaerah->nama">
    <form method="POST" action="{{ $baru ? route('perangkat-daerah.store') : route('perangkat-daerah.update', $perangkatDaerah) }}" class="max-w-3xl">
        @csrf
        @unless ($baru) @method('PUT') @endunless
        <x-card title="Data Perangkat Daerah">
            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <x-field name="kode" label="Kode" required>
                    <input id="kode" name="kode" value="{{ old('kode', $perangkatDaerah->kode) }}" class="input font-mono" placeholder="PD-0101">
                </x-field>
                <x-field name="singkatan" label="Singkatan">
                    <input id="singkatan" name="singkatan" value="{{ old('singkatan', $perangkatDaerah->singkatan) }}" class="input" placeholder="Disdik">
                </x-field>
                <x-field name="nama" label="Nama perangkat daerah" required class="sm:col-span-2">
                    <input id="nama" name="nama" value="{{ old('nama', $perangkatDaerah->nama) }}" class="input" placeholder="Dinas Pendidikan">
                </x-field>
                <x-field name="alamat" label="Alamat" class="sm:col-span-2">
                    <input id="alamat" name="alamat" value="{{ old('alamat', $perangkatDaerah->alamat) }}" class="input">
                </x-field>
                <x-field name="telepon" label="Telepon">
                    <input id="telepon" name="telepon" value="{{ old('telepon', $perangkatDaerah->telepon) }}" class="input" inputmode="tel">
                </x-field>
            </div>
        </x-card>
        <div class="mt-6 flex gap-2">
            <button class="btn-primary">Simpan</button>
            <a href="{{ $baru ? route('perangkat-daerah.index') : route('perangkat-daerah.show', $perangkatDaerah) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</x-app-layout>
