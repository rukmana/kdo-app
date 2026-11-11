<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerangkatDaerahRequest;
use App\Models\PerangkatDaerah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerangkatDaerahController extends Controller
{
    public function index(Request $request): View
    {
        $perangkatDaerah = PerangkatDaerah::cari($request->query('q'))
            ->withCount(['pegawai', 'kendaraan'])
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return view('perangkat-daerah.index', compact('perangkatDaerah'));
    }

    public function create(): View
    {
        return view('perangkat-daerah.form', ['perangkatDaerah' => new PerangkatDaerah]);
    }

    public function store(PerangkatDaerahRequest $request): RedirectResponse
    {
        $pd = PerangkatDaerah::create($request->validated());

        return redirect()->route('perangkat-daerah.show', $pd)->with('sukses', "Perangkat daerah {$pd->nama} berhasil ditambahkan.");
    }

    public function show(PerangkatDaerah $perangkatDaerah): View
    {
        $perangkatDaerah->load([
            'pegawai' => fn ($q) => $q->withCount('kendaraan')->orderBy('nama'),
            'kendaraan' => fn ($q) => $q->with('pegawai')->orderBy('nomor_polisi'),
        ]);

        return view('perangkat-daerah.show', compact('perangkatDaerah'));
    }

    public function edit(PerangkatDaerah $perangkatDaerah): View
    {
        return view('perangkat-daerah.form', compact('perangkatDaerah'));
    }

    public function update(PerangkatDaerahRequest $request, PerangkatDaerah $perangkatDaerah): RedirectResponse
    {
        $perangkatDaerah->update($request->validated());

        return redirect()->route('perangkat-daerah.show', $perangkatDaerah)->with('sukses', 'Data perangkat daerah berhasil diperbarui.');
    }

    public function destroy(PerangkatDaerah $perangkatDaerah): RedirectResponse
    {
        if ($perangkatDaerah->pegawai()->exists() || $perangkatDaerah->kendaraan()->exists()) {
            return back()->with('gagal', "{$perangkatDaerah->nama} tidak dapat dihapus karena masih memiliki pegawai atau kendaraan.");
        }

        $perangkatDaerah->delete();

        return redirect()->route('perangkat-daerah.index')->with('sukses', 'Perangkat daerah berhasil dihapus.');
    }
}
