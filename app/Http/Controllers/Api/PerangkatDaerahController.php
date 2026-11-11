<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PerangkatDaerahRequest;
use App\Http\Resources\PerangkatDaerahResource;
use App\Models\PerangkatDaerah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PerangkatDaerahController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return PerangkatDaerahResource::collection(
            PerangkatDaerah::cari($request->query('q'))
                ->withCount(['pegawai', 'kendaraan'])
                ->orderBy('kode')
                ->paginate($this->perPage($request))
                ->withQueryString()
        );
    }

    public function store(PerangkatDaerahRequest $request): JsonResponse
    {
        $pd = PerangkatDaerah::create($request->validated());

        return (new PerangkatDaerahResource($pd))
            ->additional(['message' => 'Perangkat daerah berhasil ditambahkan.'])
            ->response()->setStatusCode(201);
    }

    public function show(PerangkatDaerah $perangkatDaerah): PerangkatDaerahResource
    {
        return new PerangkatDaerahResource(
            $perangkatDaerah->load(['pegawai', 'kendaraan'])->loadCount(['pegawai', 'kendaraan'])
        );
    }

    public function update(PerangkatDaerahRequest $request, PerangkatDaerah $perangkatDaerah): PerangkatDaerahResource
    {
        $perangkatDaerah->update($request->validated());

        return (new PerangkatDaerahResource($perangkatDaerah))->additional(['message' => 'Perangkat daerah berhasil diperbarui.']);
    }

    public function destroy(PerangkatDaerah $perangkatDaerah): JsonResponse
    {
        if ($perangkatDaerah->pegawai()->exists() || $perangkatDaerah->kendaraan()->exists()) {
            return response()->json([
                'message' => 'Perangkat daerah masih memiliki pegawai atau kendaraan sehingga tidak dapat dihapus.',
            ], 409);
        }

        $perangkatDaerah->delete();

        return response()->json(['message' => 'Perangkat daerah berhasil dihapus.']);
    }
}
