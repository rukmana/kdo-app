<?php

namespace App\Http\Controllers;

use App\Http\Requests\PegawaiRequest;
use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use App\Support\Referensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $pegawai = Pegawai::cari($request->query('q'))
            ->when($request->query('perangkat_daerah_id'), fn ($q, $id) => $q->where('perangkat_daerah_id', $id))
            ->with('perangkatDaerah')
            ->withCount('kendaraan')
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('pegawai.index', [
            'pegawai' => $pegawai,
            'daftarPd' => PerangkatDaerah::orderBy('nama')->pluck('nama', 'id'),
        ]);
    }

    public function create(Request $request): View
    {
        return $this->form(new Pegawai(['perangkat_daerah_id' => $request->query('perangkat_daerah_id')]));
    }

    public function store(PegawaiRequest $request): RedirectResponse
    {
        $pegawai = Pegawai::create($request->validated());

        return redirect()->route('pegawai.show', $pegawai)->with('sukses', "Pegawai {$pegawai->nama} berhasil ditambahkan.");
    }

    public function show(Pegawai $pegawai): View
    {
        $pegawai->load([
            'perangkatDaerah',
            'kendaraan' => fn ($q) => $q->orderBy('nomor_polisi'),
            'riwayatPenanggungJawab' => fn ($q) => $q->with('kendaraan')->latest('tanggal_mulai')->latest('id'),
        ]);

        return view('pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai): View
    {
        return $this->form($pegawai);
    }

    public function update(PegawaiRequest $request, Pegawai $pegawai): RedirectResponse
    {
        $data = $request->validated();

        if ((int) $data['perangkat_daerah_id'] !== $pegawai->perangkat_daerah_id && $pegawai->kendaraan()->exists()) {
            return back()->withInput()->withErrors([
                'perangkat_daerah_id' => 'Pegawai masih menjadi penanggung jawab kendaraan di perangkat daerah lama. Alihkan kendaraannya terlebih dahulu.',
            ]);
        }

        $pegawai->update($data);

        return redirect()->route('pegawai.show', $pegawai)->with('sukses', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        if ($pegawai->kendaraan()->exists()) {
            return back()->with('gagal', "{$pegawai->nama} masih menjadi penanggung jawab kendaraan dan tidak dapat dihapus.");
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('sukses', 'Pegawai berhasil dihapus.');
    }

    private function form(Pegawai $pegawai): View
    {
        return view('pegawai.form', [
            'pegawai' => $pegawai,
            'daftarPd' => PerangkatDaerah::orderBy('nama')->pluck('nama', 'id'),
            'golongan' => Referensi::GOLONGAN,
        ]);
    }
}
