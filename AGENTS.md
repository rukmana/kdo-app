# AGENTS.md — Catatan Penggunaan AI

## Alat
- **Claude Code** (aplikasi desktop Claude), model **Claude Opus 5.5** (`claude-opus-5-5`).

## Instruksi yang diberikan ke AI
1. Lampiran soal "Ujian Praktik Junior Programmer" (JP.pdf) dengan perintah: *"kerjakan"*.
2. Pilihan dari penilai: buat **solusi acuan**, stack **Laravel + MySQL**, full stack.

## Pedoman yang diikuti agen
- Kerjakan ketiga entitas wajib (Kendaraan, PNS, Perangkat Daerah) dengan CRUD lengkap, di web **dan** REST API.
- Satu sumber validasi: Form Request dipakai bersama controller web & API.
- Relasi dijaga di dua lapis: foreign key DB (`RESTRICT` / `SET NULL` / `CASCADE`) dan pengecekan di aplikasi
  (pesan ramah di web, `409 Conflict` di API).
- Aturan tambahan yang relevan: penanggung jawab harus dari PD pemilik kendaraan; riwayat penanggung jawab
  dicatat otomatis lewat observer.
- Bahasa antarmuka, pesan validasi, dan pesan error API: **Bahasa Indonesia**.
- Data contoh realistis tapi **fiktif** (tidak memakai data pribadi orang nyata).
- Setiap fitur diverifikasi: `php artisan test` (21 test) + uji manual di browser + `curl`.

## Keputusan teknis & alasannya
| Keputusan | Alasan |
|---|---|
| Laravel 13 + Blade + Tailwind | Full stack dalam satu codebase, cepat dinilai tanpa build frontend terpisah |
| Sanctum personal access token | Autentikasi API sederhana berbasis Bearer token |
| OpenAPI + Swagger UI di `/docs` | Dokumentasi API yang bisa langsung dicoba |
| Tabel `riwayat_penanggung_jawab` | Entitas tambahan untuk menelusuri siapa memegang kendaraan kapan |
| Dump MySQL dibuat dari migrasi (grammar MySQL) + data seeder | Server MySQL di mesin pengembang tidak dapat dijalankan (library `protobuf` hilang setelah update Homebrew); pengembangan & test memakai SQLite. Struktur dump identik dengan hasil `php artisan migrate` di MySQL |

## Cara agen bekerja di repo ini
- PHP: gunakan PHP ≥ 8.3 (`php -v`); jalankan test dengan `php artisan test` sebelum menyatakan selesai.
- Setelah mengubah view/CSS: `npm run build`.
- Jangan menyimpan data pribadi asli di seeder.
