<?php

namespace Tests\Feature\Api;

use App\Models\Kendaraan;
use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KendaraanApiTest extends TestCase
{
    use RefreshDatabase;

    private PerangkatDaerah $pd;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
        $this->pd = PerangkatDaerah::factory()->create();
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'nomor_polisi' => 'b 1999  pqa',
            'jenis' => 'Roda 4',
            'merek' => 'Toyota',
            'tipe' => 'Kijang Innova Zenix',
            'tahun_produksi' => 2024,
            'warna' => 'Hitam',
            'kondisi' => 'Baik',
            'perangkat_daerah_id' => $this->pd->id,
        ], $override);
    }

    public function test_daftar_kendaraan_terpaginasi_dan_bisa_difilter(): void
    {
        Kendaraan::factory()->count(3)->for($this->pd)->create(['kondisi' => 'Baik']);
        Kendaraan::factory()->for($this->pd)->create(['kondisi' => 'Rusak Berat']);

        $this->getJson('/api/kendaraan?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 4);

        $this->getJson('/api/kendaraan?kondisi=Rusak Berat')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.kondisi', 'Rusak Berat');

        $this->getJson('/api/kendaraan?kondisi=Hancur')->assertStatus(422);
    }

    public function test_tambah_kendaraan_menormalisasi_nomor_polisi(): void
    {
        $this->postJson('/api/kendaraan', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.nomor_polisi', 'B 1999 PQA')
            ->assertJsonPath('data.perangkat_daerah.id', $this->pd->id);

        $this->assertDatabaseHas('kendaraan', ['nomor_polisi' => 'B 1999 PQA']);
    }

    public function test_validasi_wajib_format_dan_keunikan(): void
    {
        $this->postJson('/api/kendaraan', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nomor_polisi', 'jenis', 'merek', 'tipe', 'tahun_produksi', 'warna', 'kondisi', 'perangkat_daerah_id']);

        $this->postJson('/api/kendaraan', $this->payload(['nomor_polisi' => '1234', 'kondisi' => 'Hilang', 'tahun_produksi' => 1950]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nomor_polisi', 'kondisi', 'tahun_produksi']);

        Kendaraan::factory()->for($this->pd)->create(['nomor_polisi' => 'B 1999 PQA']);
        $this->postJson('/api/kendaraan', $this->payload())
            ->assertStatus(422)
            ->assertJsonValidationErrors('nomor_polisi');
    }

    public function test_penanggung_jawab_harus_dari_perangkat_daerah_yang_sama(): void
    {
        $pegawaiLain = Pegawai::factory()->create(); // PD berbeda

        $this->postJson('/api/kendaraan', $this->payload(['pegawai_id' => $pegawaiLain->id]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('pegawai_id');

        $pegawai = Pegawai::factory()->for($this->pd)->create();
        $this->postJson('/api/kendaraan', $this->payload(['pegawai_id' => $pegawai->id]))
            ->assertCreated()
            ->assertJsonPath('data.penanggung_jawab.id', $pegawai->id);
    }

    public function test_detail_ubah_dan_hapus(): void
    {
        $kendaraan = Kendaraan::factory()->for($this->pd)->create();

        $this->getJson("/api/kendaraan/{$kendaraan->id}")
            ->assertOk()
            ->assertJsonPath('data.nomor_polisi', $kendaraan->nomor_polisi);

        $this->putJson("/api/kendaraan/{$kendaraan->id}", $this->payload([
            'nomor_polisi' => $kendaraan->nomor_polisi,
            'kondisi' => 'Rusak Ringan',
        ]))->assertOk()->assertJsonPath('data.kondisi', 'Rusak Ringan');

        $this->deleteJson("/api/kendaraan/{$kendaraan->id}")->assertOk();
        $this->assertDatabaseMissing('kendaraan', ['id' => $kendaraan->id]);

        $this->getJson("/api/kendaraan/{$kendaraan->id}")->assertNotFound()->assertJsonStructure(['message']);
    }

    public function test_pergantian_penanggung_jawab_tercatat_di_riwayat(): void
    {
        [$a, $b] = Pegawai::factory()->count(2)->for($this->pd)->create();
        $kendaraan = Kendaraan::factory()->for($this->pd)->create(['pegawai_id' => $a->id]);

        $this->putJson("/api/kendaraan/{$kendaraan->id}", $this->payload([
            'nomor_polisi' => $kendaraan->nomor_polisi,
            'pegawai_id' => $b->id,
        ]))->assertOk();

        $riwayat = $this->getJson("/api/kendaraan/{$kendaraan->id}")->json('data.riwayat_penanggung_jawab');

        $this->assertCount(2, $riwayat);
        $this->assertSame($b->id, $riwayat[0]['pegawai_id']);
        $this->assertNull($riwayat[0]['tanggal_selesai']);
        $this->assertNotNull($riwayat[1]['tanggal_selesai']);
    }
}
