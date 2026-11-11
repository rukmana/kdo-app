<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KendaraanRequest;
use App\Http\Resources\KendaraanResource;
use App\Models\Kendaraan;
use App\Support\Referensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $filter = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kondisi' => ['nullable', Rule::in(Referensi::KONDISI)],
            'jenis' => ['nullable', Rule::in(Referensi::JENIS_KENDARAAN)],
            'perangkat_daerah_id' => ['nullable', 'integer'],
        ]);

        return KendaraanResource::collection(
            Kendaraan::filter($filter)
                ->with(['perangkatDaerah', 'pegawai'])
                ->orderBy('nomor_polisi')
                ->paginate($this->perPage($request))
                ->withQueryString()
        );
    }

    public function store(KendaraanRequest $request): JsonResponse
    {
        $kendaraan = Kendaraan::create($request->validated())->load(['perangkatDaerah', 'pegawai']);

        return (new KendaraanResource($kendaraan))
            ->additional(['message' => 'Kendaraan berhasil ditambahkan.'])
            ->response()->setStatusCode(201);
    }

    public function show(Kendaraan $kendaraan): KendaraanResource
    {
        return new KendaraanResource($kendaraan->load(['perangkatDaerah', 'pegawai', 'riwayatPenanggungJawab.pegawai']));
    }

    public function update(KendaraanRequest $request, Kendaraan $kendaraan): KendaraanResource
    {
        $kendaraan->update($request->validated());

        return (new KendaraanResource($kendaraan->load(['perangkatDaerah', 'pegawai'])))
            ->additional(['message' => 'Kendaraan berhasil diperbarui.']);
    }

    public function destroy(Kendaraan $kendaraan): JsonResponse
    {
        $kendaraan->delete();

        return response()->json(['message' => 'Kendaraan berhasil dihapus.']);
    }
}
