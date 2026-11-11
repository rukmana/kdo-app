<?php

namespace Tests\Feature\Api;

use App\Models\Kendaraan;
use App\Models\Pegawai;
use App\Models\PerangkatDaerah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PegawaiDanPerangkatDaerahApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_crud_perangkat_daerah(): void
    {
        $id = $this->postJson('/api/perangkat-daerah', ['kode' => 'PD-0301', 'nama' => 'Dinas Sosial', 'singkatan' => 'Dinsos'])
            ->assertCreated()
            ->json('data.id');

        $this->postJson('/api/perangkat-daerah', ['kode' => 'PD-0301', 'nama' => 'Dinas Sosial'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['kode', 'nama']);

        $this->putJson("/api/perangkat-daerah/{$id}", ['kode' => 'PD-0301', 'nama' => 'Dinas Sosial dan Pemberdayaan'])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Dinas Sosial dan Pemberdayaan');

        $this->getJson('/api/perangkat-daerah?q=Sosial')->assertOk()->assertJsonCount(1, 'data');

        $this->deleteJson("/api/perangkat-daerah/{$id}")->assertOk();
    }

    public function test_perangkat_daerah_yang_masih_dipakai_tidak_bisa_dihapus(): void
    {
        $pd = PerangkatDaerah::factory()->create();
        Pegawai::factory()->for($pd)->create();

        $this->deleteJson("/api/perangkat-daerah/{$pd->id}")->assertStatus(409);
        $this->assertDatabaseHas('perangkat_daerah', ['id' => $pd->id]);
    }

    public function test_crud_pegawai_dengan_validasi_nip(): void
    {
        $pd = PerangkatDaerah::factory()->create();
        $data = [
            'nip' => '19960101 202203 1 001',
            'nama' => 'Andi Wijaya',
            'jabatan' => 'Pengurus Barang',
            'golongan' => 'III/a',
            'perangkat_daerah_id' => $pd->id,
        ];

        $id = $this->postJson('/api/pegawai', $data)
            ->assertCreated()
            ->assertJsonPath('data.nip', '199601012022031001')
            ->json('data.id');

        $this->postJson('/api/pegawai', [...$data, 'nip' => '12345', 'golongan' => 'V/a'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nip', 'golongan']);

        $this->postJson('/api/pegawai', $data)->assertStatus(422)->assertJsonValidationErrors('nip');

        $this->putJson("/api/pegawai/{$id}", [...$data, 'jabatan' => 'Kepala Seksi'])
            ->assertOk()
            ->assertJsonPath('data.jabatan', 'Kepala Seksi');

        $this->getJson("/api/pegawai/{$id}")->assertOk()->assertJsonPath('data.perangkat_daerah.id', $pd->id);
        $this->deleteJson("/api/pegawai/{$id}")->assertOk();
    }

    public function test_pegawai_penanggung_jawab_tidak_bisa_dihapus(): void
    {
        $pd = PerangkatDaerah::factory()->create();
        $pegawai = Pegawai::factory()->for($pd)->create();
        Kendaraan::factory()->for($pd)->create(['pegawai_id' => $pegawai->id]);

        $this->deleteJson("/api/pegawai/{$pegawai->id}")->assertStatus(409);
    }

    public function test_dashboard_mengembalikan_ringkasan(): void
    {
        $pd = PerangkatDaerah::factory()->create();
        Kendaraan::factory()->for($pd)->create(['kondisi' => 'Baik']);
        Kendaraan::factory()->for($pd)->create(['kondisi' => 'Rusak Berat']);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('data.total_kendaraan', 2)
            ->assertJsonPath('data.per_kondisi.Baik', 1)
            ->assertJsonPath('data.per_kondisi.Rusak Berat', 1);
    }
}
