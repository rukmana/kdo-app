<?php

namespace Tests\Feature\Web;

use App\Models\Kendaraan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertSee('Masuk');
    }

    public function test_login_web_berhasil(): void
    {
        $this->post('/login', ['email' => 'admin@kdo.test', 'password' => 'password'])->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_semua_halaman_utama_dapat_dibuka(): void
    {
        $this->actingAs(User::first());
        $kendaraan = Kendaraan::with(['pegawai', 'perangkatDaerah'])->whereNotNull('pegawai_id')->first();

        foreach ([
            '/', '/kendaraan', '/kendaraan/create', "/kendaraan/{$kendaraan->id}", "/kendaraan/{$kendaraan->id}/edit",
            '/pegawai', '/pegawai/create', "/pegawai/{$kendaraan->pegawai_id}", "/pegawai/{$kendaraan->pegawai_id}/edit",
            '/perangkat-daerah', '/perangkat-daerah/create', "/perangkat-daerah/{$kendaraan->perangkat_daerah_id}",
            "/perangkat-daerah/{$kendaraan->perangkat_daerah_id}/edit", '/kendaraan?kondisi=Rusak+Berat&q=toyota',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_tambah_dan_hapus_kendaraan_lewat_form(): void
    {
        $this->actingAs(User::first());
        $pdId = Kendaraan::first()->perangkat_daerah_id;

        $this->post('/kendaraan', [
            'nomor_polisi' => 'B 2024 PQA', 'jenis' => 'Roda 4', 'merek' => 'Wuling', 'tipe' => 'Air ev',
            'tahun_produksi' => 2024, 'warna' => 'Putih', 'kondisi' => 'Baik', 'perangkat_daerah_id' => $pdId,
            'pegawai_id' => '',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $baru = Kendaraan::where('nomor_polisi', 'B 2024 PQA')->firstOrFail();

        $this->delete("/kendaraan/{$baru->id}")->assertRedirect('/kendaraan');
        $this->assertModelMissing($baru);
    }

    public function test_form_menampilkan_error_validasi(): void
    {
        $this->actingAs(User::first());

        $this->from('/kendaraan/create')->post('/kendaraan', ['nomor_polisi' => 'xx'])
            ->assertRedirect('/kendaraan/create')
            ->assertSessionHasErrors(['nomor_polisi', 'merek', 'perangkat_daerah_id']);
    }

    public function test_ekspor_csv(): void
    {
        $this->actingAs(User::first());

        $res = $this->get('/kendaraan/export?kondisi=Baik')->assertOk();
        $this->assertStringContainsString('Nomor Polisi', $res->streamedContent());
        $this->assertStringNotContainsString('Rusak Berat', $res->streamedContent());
    }
}
