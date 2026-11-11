<?php

namespace App\Http\Controllers;

use App\Http\Requests\KendaraanRequest;
use App\Models\Kendaraan;
use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use App\Support\Referensi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KendaraanController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->only(['q', 'kondisi', 'jenis', 'perangkat_daerah_id']);

        $kendaraan = Kendaraan::filter($filter)
            ->with(['perangkatDaerah', 'pegawai'])
            ->orderBy('nomor_polisi')
            ->paginate(10)
            ->withQueryString();

        return view('kendaraan.index', [
            'kendaraan' => $kendaraan,
            'filter' => $filter,
            'daftarPd' => PerangkatDaerah::orderBy('nama')->pluck('nama', 'id'),
        ]);
    }

    public function create(Request $request): View
    {
        return $this->form(new Kendaraan([
            'kondisi' => 'Baik',
            'jenis' => 'Roda 4',
            'perangkat_daerah_id' => $request->query('perangkat_daerah_id'),
        ]));
    }

    public function store(KendaraanRequest $request): RedirectResponse
    {
        $kendaraan = Kendaraan::create($request->validated());

        return redirect()->route('kendaraan.show', $kendaraan)->with('sukses', "Kendaraan {$kendaraan->nomor_polisi} berhasil ditambahkan.");
    }

    public function show(Kendaraan $kendaraan): View
    {
        $kendaraan->load(['perangkatDaerah', 'pegawai', 'riwayatPenanggungJawab.pegawai']);

        return view('kendaraan.show', compact('kendaraan'));
    }

    public function edit(Kendaraan $kendaraan): View
    {
        return $this->form($kendaraan);
    }

    public function update(KendaraanRequest $request, Kendaraan $kendaraan): RedirectResponse
    {
        $kendaraan->update($request->validated());

        return redirect()->route('kendaraan.show', $kendaraan)->with('sukses', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan): RedirectResponse
    {
        $kendaraan->delete();

        return redirect()->route('kendaraan.index')->with('sukses', "Kendaraan {$kendaraan->nomor_polisi} berhasil dihapus.");
    }

    /** Ekspor daftar kendaraan (mengikuti filter aktif) ke CSV. */
    public function export(Request $request): StreamedResponse
    {
        $filter = $request->only(['q', 'kondisi', 'jenis', 'perangkat_daerah_id']);
        $namaFile = 'kendaraan-dinas-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($filter) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nomor Polisi', 'Jenis', 'Merek', 'Tipe', 'Tahun', 'Warna', 'Kondisi', 'Nomor Rangka', 'Nomor Mesin', 'Perangkat Daerah', 'Penanggung Jawab', 'NIP Penanggung Jawab']);

            Kendaraan::filter($filter)->with(['perangkatDaerah', 'pegawai'])->orderBy('nomor_polisi')
                ->lazy()
                ->each(fn (Kendaraan $k) => fputcsv($out, [
                    $k->nomor_polisi, $k->jenis, $k->merek, $k->tipe, $k->tahun_produksi, $k->warna, $k->kondisi,
                    $k->nomor_rangka, $k->nomor_mesin, $k->perangkatDaerah->nama, $k->pegawai?->nama, $k->pegawai?->nip,
                ]));

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv']);
    }

    private function form(Kendaraan $kendaraan): View
    {
        return view('kendaraan.form', [
            'kendaraan' => $kendaraan,
            'daftarPd' => PerangkatDaerah::orderBy('nama')->pluck('nama', 'id'),
            'daftarPegawai' => Pegawai::orderBy('nama')->get(['id', 'nama', 'nip', 'perangkat_daerah_id']),
            'kondisi' => Referensi::KONDISI,
            'jenis' => Referensi::JENIS_KENDARAAN,
        ]);
    }
}
