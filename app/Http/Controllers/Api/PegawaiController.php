<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRequest;
use App\Http\Resources\PegawaiResource;
use App\Models\Pegawai;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PegawaiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return PegawaiResource::collection(
            Pegawai::cari($request->query('q'))
                ->when($request->query('perangkat_daerah_id'), fn ($q, $id) => $q->where('perangkat_daerah_id', $id))
                ->with('perangkatDaerah')
                ->withCount('kendaraan')
                ->orderBy('nama')
                ->paginate($this->perPage($request))
                ->withQueryString()
        );
    }

    public function store(PegawaiRequest $request): JsonResponse
    {
        $pegawai = Pegawai::create($request->validated())->load('perangkatDaerah');

        return (new PegawaiResource($pegawai))
            ->additional(['message' => 'Pegawai berhasil ditambahkan.'])
            ->response()->setStatusCode(201);
    }

    public function show(Pegawai $pegawai): PegawaiResource
    {
        return new PegawaiResource($pegawai->load(['perangkatDaerah', 'kendaraan'])->loadCount('kendaraan'));
    }

    public function update(PegawaiRequest $request, Pegawai $pegawai): PegawaiResource|JsonResponse
    {
        $data = $request->validated();

        if ((int) $data['perangkat_daerah_id'] !== $pegawai->perangkat_daerah_id && $pegawai->kendaraan()->exists()) {
            return response()->json([
                'message' => 'Pegawai masih menjadi penanggung jawab kendaraan di perangkat daerah lama.',
                'errors' => ['perangkat_daerah_id' => ['Alihkan kendaraan pegawai ini terlebih dahulu.']],
            ], 422);
        }

        $pegawai->update($data);

        return (new PegawaiResource($pegawai->load('perangkatDaerah')))->additional(['message' => 'Pegawai berhasil diperbarui.']);
    }

    public function destroy(Pegawai $pegawai): JsonResponse
    {
        if ($pegawai->kendaraan()->exists()) {
            return response()->json([
                'message' => 'Pegawai masih menjadi penanggung jawab kendaraan sehingga tidak dapat dihapus.',
            ], 409);
        }

        $pegawai->delete();

        return response()->json(['message' => 'Pegawai berhasil dihapus.']);
    }
}
