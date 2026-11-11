-- SIM KDO — dump database MySQL 8
-- Dibuat: 2026-11-11 07:39:03
-- Struktur dari migrasi Laravel (grammar MySQL 8), data dari seeder. Impor: mysql -u root -p < database/dump/kdo_db.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `kdo_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kdo_db`;

DROP TABLE IF EXISTS `riwayat_penanggung_jawab`;
DROP TABLE IF EXISTS `kendaraan`;
DROP TABLE IF EXISTS `pegawai`;
DROP TABLE IF EXISTS `perangkat_daerah`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;

create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `email` varchar(255) not null, `email_verified_at` timestamp null, `password` varchar(255) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `users` add unique `users_email_unique`(`email`);
create table `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp null, primary key (`email`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
create table `sessions` (`id` varchar(255) not null, `user_id` bigint unsigned null, `ip_address` varchar(45) null, `user_agent` text null, `payload` longtext not null, `last_activity` int not null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `sessions` add index `sessions_user_id_index`(`user_id`);
alter table `sessions` add index `sessions_last_activity_index`(`last_activity`);
create table `cache` (`key` varchar(255) not null, `value` mediumtext not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `cache` add index `cache_expiration_index`(`expiration`);
create table `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `cache_locks` add index `cache_locks_expiration_index`(`expiration`);
create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(255) not null, `payload` longtext not null, `attempts` smallint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `jobs` add index `jobs_queue_index`(`queue`);
create table `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` int not null, `pending_jobs` int not null, `failed_jobs` int not null, `failed_job_ids` longtext not null, `options` mediumtext null, `cancelled_at` int null, `created_at` int not null, `finished_at` int null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(255) not null, `connection` varchar(255) not null, `queue` varchar(255) not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp not null default CURRENT_TIMESTAMP) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `failed_jobs` add index `failed_jobs_connection_queue_failed_at_index`(`connection`, `queue`, `failed_at`);
alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`);
create table `personal_access_tokens` (`id` bigint unsigned not null auto_increment primary key, `tokenable_type` varchar(255) not null, `tokenable_id` bigint unsigned not null, `name` text not null, `token` varchar(64) not null, `abilities` text null, `last_used_at` timestamp null, `expires_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `personal_access_tokens` add index `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`);
alter table `personal_access_tokens` add unique `personal_access_tokens_token_unique`(`token`);
alter table `personal_access_tokens` add index `personal_access_tokens_expires_at_index`(`expires_at`);
create table `perangkat_daerah` (`id` bigint unsigned not null auto_increment primary key, `kode` varchar(20) not null, `nama` varchar(150) not null, `singkatan` varchar(30) null, `alamat` varchar(255) null, `telepon` varchar(20) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `perangkat_daerah` add unique `perangkat_daerah_kode_unique`(`kode`);
alter table `perangkat_daerah` add unique `perangkat_daerah_nama_unique`(`nama`);
create table `pegawai` (`id` bigint unsigned not null auto_increment primary key, `nip` char(18) not null, `nama` varchar(150) not null, `jabatan` varchar(150) not null, `golongan` varchar(10) not null, `perangkat_daerah_id` bigint unsigned not null, `no_hp` varchar(20) null, `email` varchar(150) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `pegawai` add constraint `pegawai_perangkat_daerah_id_foreign` foreign key (`perangkat_daerah_id`) references `perangkat_daerah` (`id`) on delete restrict;
alter table `pegawai` add index `pegawai_nama_index`(`nama`);
alter table `pegawai` add unique `pegawai_nip_unique`(`nip`);
alter table `pegawai` add unique `pegawai_email_unique`(`email`);
create table `kendaraan` (`id` bigint unsigned not null auto_increment primary key, `nomor_polisi` varchar(12) not null, `jenis` varchar(20) not null, `merek` varchar(50) not null, `tipe` varchar(100) not null, `tahun_produksi` smallint unsigned not null, `warna` varchar(30) not null, `kondisi` enum('Baik', 'Rusak Ringan', 'Rusak Berat') not null default 'Baik', `nomor_rangka` varchar(30) null, `nomor_mesin` varchar(30) null, `perangkat_daerah_id` bigint unsigned not null, `pegawai_id` bigint unsigned null, `keterangan` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `kendaraan` add constraint `kendaraan_perangkat_daerah_id_foreign` foreign key (`perangkat_daerah_id`) references `perangkat_daerah` (`id`) on delete restrict;
alter table `kendaraan` add constraint `kendaraan_pegawai_id_foreign` foreign key (`pegawai_id`) references `pegawai` (`id`) on delete set null;
alter table `kendaraan` add index `kendaraan_kondisi_index`(`kondisi`);
alter table `kendaraan` add unique `kendaraan_nomor_polisi_unique`(`nomor_polisi`);
alter table `kendaraan` add unique `kendaraan_nomor_rangka_unique`(`nomor_rangka`);
alter table `kendaraan` add unique `kendaraan_nomor_mesin_unique`(`nomor_mesin`);
create table `riwayat_penanggung_jawab` (`id` bigint unsigned not null auto_increment primary key, `kendaraan_id` bigint unsigned not null, `pegawai_id` bigint unsigned not null, `tanggal_mulai` date not null, `tanggal_selesai` date null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `riwayat_penanggung_jawab` add constraint `riwayat_penanggung_jawab_kendaraan_id_foreign` foreign key (`kendaraan_id`) references `kendaraan` (`id`) on delete cascade;
alter table `riwayat_penanggung_jawab` add constraint `riwayat_penanggung_jawab_pegawai_id_foreign` foreign key (`pegawai_id`) references `pegawai` (`id`) on delete cascade;
alter table `riwayat_penanggung_jawab` add index `riwayat_penanggung_jawab_kendaraan_id_tanggal_selesai_index`(`kendaraan_id`, `tanggal_selesai`);

-- Data `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator BMD', 'admin@kdo.test', NULL, '$2y$12$jzzsJMBC.MxBCNR3IkPMBuQM9scVEs2eCZ7RKIt5FMKTuXhM0C7um', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03');

-- Data `perangkat_daerah`
INSERT INTO `perangkat_daerah` (`id`, `kode`, `nama`, `singkatan`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
(1, 'PD-0101', 'Dinas Pendidikan', 'Disdik', 'Jl. Gatot Subroto Kav. 40-41, Jakarta Selatan', '021-5255385', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(2, 'PD-0102', 'Dinas Kesehatan', 'Dinkes', 'Jl. Kesehatan No. 10, Jakarta Pusat', '021-3522767', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(3, 'PD-0103', 'Dinas Bina Marga', 'DBM', 'Jl. Taman Jatibaru No. 1, Jakarta Pusat', '021-3843530', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(4, 'PD-0104', 'Dinas Sumber Daya Air', 'DSDA', 'Jl. Taman Jatibaru No. 1, Jakarta Pusat', '021-3840522', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(5, 'PD-0105', 'Dinas Lingkungan Hidup', 'DLH', 'Jl. Mandala V No. 67, Jakarta Timur', '021-8092744', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(6, 'PD-0106', 'Dinas Perhubungan', 'Dishub', 'Jl. Taman Jatibaru No. 1, Jakarta Pusat', '021-3455262', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(7, 'PD-0201', 'Badan Pengelolaan Aset Daerah', 'BPAD', 'Jl. Medan Merdeka Selatan No. 8-9, Jakarta Pusat', '021-3822255', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(8, 'PD-0202', 'Badan Kepegawaian Daerah', 'BKD', 'Jl. Medan Merdeka Selatan No. 8-9, Jakarta Pusat', '021-3823280', '2026-11-11 07:39:03', '2026-11-11 07:39:03');

-- Data `pegawai`
INSERT INTO `pegawai` (`id`, `nip`, `nama`, `jabatan`, `golongan`, `perangkat_daerah_id`, `no_hp`, `email`, `created_at`, `updated_at`) VALUES
(1, '198203152008011004', 'Ahmad Fauzi', 'Kepala Subbagian Umum', 'III/d', 1, '081234560101', 'ahmad.fauzi@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(2, '199005222015032001', 'Siti Rahmawati', 'Pengurus Barang', 'III/a', 1, '081234560102', 'siti.rahmawati@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(3, '197811042005011002', 'Bambang Sutrisno', 'Kepala Subbagian Umum', 'IV/a', 2, '081234560201', 'bambang.sutrisno@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(4, '199207172019022003', 'Dewi Lestari', 'Pengurus Barang', 'II/c', 2, '081234560202', 'dewi.lestari@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(5, '198506302010011005', 'Rudi Hartono', 'Kepala Seksi Pemeliharaan Jalan', 'III/c', 3, '081234560301', 'rudi.hartono@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(6, '199111092017031002', 'Yusuf Maulana', 'Pengemudi', 'II/a', 3, '081234560302', 'yusuf.maulana@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(7, '198008202006042001', 'Rina Marlina', 'Kepala Subbagian Umum', 'III/d', 4, '081234560401', 'rina.marlina@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(8, '199302142020121004', 'Hendra Saputra', 'Pengurus Barang', 'II/b', 4, '081234560402', 'hendra.saputra@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(9, '197604122003121003', 'Agus Salim', 'Kepala Seksi Pengangkutan Sampah', 'IV/a', 5, '081234560501', 'agus.salim@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(10, '198812012014031001', 'Fajar Nugroho', 'Pengemudi', 'II/d', 5, '081234560502', 'fajar.nugroho@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(11, '198409252009011006', 'Wahyu Pratama', 'Kepala Subbagian Umum', 'III/c', 6, '081234560601', 'wahyu.pratama@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(12, '199404032022032002', 'Nur Aini', 'Pengurus Barang', 'II/c', 6, '081234560602', 'nur.aini@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(13, '197912182005011001', 'Eko Prasetyo', 'Kepala Bidang Pemanfaatan Aset', 'IV/b', 7, '081234560701', 'eko.prasetyo@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(14, '199106062016092001', 'Maya Anggraini', 'Analis Barang Milik Daerah', 'III/b', 7, '081234560702', 'maya.anggraini@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(15, '198305112009032004', 'Lina Kusuma', 'Kepala Subbagian Umum', 'III/d', 8, '081234560801', 'lina.kusuma@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(16, '199508282021021001', 'Rizky Ramadhan', 'Pengurus Barang', 'II/b', 8, '081234560802', 'rizky.ramadhan@contoh.go.id', '2026-11-11 07:39:03', '2026-11-11 07:39:03');

-- Data `kendaraan`
INSERT INTO `kendaraan` (`id`, `nomor_polisi`, `jenis`, `merek`, `tipe`, `tahun_produksi`, `warna`, `kondisi`, `nomor_rangka`, `nomor_mesin`, `perangkat_daerah_id`, `pegawai_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'B 1101 PQA', 'Roda 4', 'Toyota', 'Kijang Innova 2.0 G', 2019, 'Hitam', 'Baik', 'MHFJW8EM5K1001101', '1TR7101101', 1, 1, 'Kendaraan operasional kepala dinas', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(2, 'B 1102 PQA', 'Roda 4', 'Toyota', 'Avanza 1.3 E', 2016, 'Silver', 'Rusak Ringan', 'MHKM1BA2JGK001102', '1NR7101102', 1, 2, 'AC tidak dingin, menunggu servis', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(3, 'B 3201 PQB', 'Roda 2', 'Honda', 'Vario 125', 2021, 'Hitam', 'Baik', 'MH1JM9113MK003201', 'JM91E3003201', 1, NULL, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(4, 'B 1201 PQA', 'Roda 4', 'Toyota', 'Hiace Ambulans', 2020, 'Putih', 'Baik', 'JTFSS22P0L0001201', '2KD7201201', 2, 3, 'Ambulans puskesmas keliling', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(5, 'B 1202 PQA', 'Roda 4', 'Mitsubishi', 'Xpander GLS', 2022, 'Putih', 'Baik', 'MK2NCWHANNJ001202', '4A917201202', 2, 4, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(6, 'B 3202 PQB', 'Roda 2', 'Yamaha', 'NMAX 155', 2018, 'Biru', 'Rusak Berat', 'MH3SG3190JK003202', 'G3E4E3202', 2, NULL, 'Rangka patah, diusulkan penghapusan', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(7, 'B 9301 PQC', 'Roda 6', 'Mitsubishi', 'Colt Diesel FE 74', 2017, 'Kuning', 'Baik', 'MHMFE74P5HK009301', '4D34T9301', 3, 5, 'Truk pengangkut material perbaikan jalan', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(8, 'B 1301 PQA', 'Roda 4', 'Toyota', 'Hilux Single Cabin', 2018, 'Kuning', 'Rusak Ringan', 'MR0HA3CD1J0001301', '2GD7301301', 3, 6, 'Ban perlu diganti', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(9, 'B 1401 PQA', 'Roda 4', 'Toyota', 'Fortuner 2.4 VRZ', 2021, 'Hitam', 'Baik', 'MHFGB8GS3M0001401', '2GD7401401', 4, 7, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(10, 'B 9401 PQC', 'Roda 6', 'Isuzu', 'Elf NMR 71', 2015, 'Biru', 'Rusak Berat', 'MHCNMR71HFJ009401', '4HG19401', 4, NULL, 'Mesin turun, biaya perbaikan melebihi nilai buku', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(11, 'B 3401 PQB', 'Roda 2', 'Honda', 'Beat CBS', 2020, 'Merah', 'Baik', 'MH1JM8116LK003401', 'JM81E3003401', 4, 8, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(12, 'B 9501 PQC', 'Roda 6', 'Hino', 'Dutro 130 MDL', 2019, 'Oranye', 'Baik', 'MJEC1JG43K5009501', 'W04DTR9501', 5, 9, 'Truk compactor sampah', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(13, 'B 9502 PQC', 'Roda 6', 'Hino', 'Dutro 110 SDL', 2016, 'Oranye', 'Rusak Ringan', 'MJEC1JG43G5009502', 'W04DTR9502', 5, 10, 'Hidrolik bocor', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(14, 'B 1601 PQA', 'Roda 4', 'Suzuki', 'Ertiga GX', 2019, 'Putih', 'Baik', 'MHYKZE81SKJ001601', 'K15B7601601', 6, 11, 'Kendaraan patroli', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(15, 'B 3601 PQB', 'Roda 2', 'Yamaha', 'Vixion 150', 2017, 'Putih', 'Baik', 'MH3RG1820HK003601', 'G3D4E3601', 6, 12, 'Motor patroli lalu lintas', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(16, 'B 1701 PQA', 'Roda 4', 'Toyota', 'Camry 2.5 V', 2020, 'Hitam', 'Baik', 'MR053REH0L4001701', 'A25A7701701', 7, 13, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(17, 'B 1702 PQA', 'Roda 4', 'Daihatsu', 'Gran Max Blind Van', 2014, 'Silver', 'Rusak Berat', 'MHKV1AA2JEK001702', '3SZ7701702', 7, NULL, 'Menunggu proses lelang', '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(18, 'B 1703 PQA', 'Roda 4', 'Honda', 'CR-V 1.5 Turbo', 2023, 'Abu-abu', 'Baik', 'MHRRW1850PJ001703', 'L15B7701703', 7, 14, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(19, 'B 1801 PQA', 'Roda 4', 'Toyota', 'Kijang Innova Zenix', 2024, 'Putih', 'Baik', 'MHFAB8GS6R0001801', 'M20A7801801', 8, 15, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(20, 'B 3801 PQB', 'Roda 2', 'Honda', 'PCX 160', 2023, 'Putih', 'Baik', 'MH1KF2117PK003801', 'KF21E3003801', 8, 16, NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03');

-- Data `riwayat_penanggung_jawab`
INSERT INTO `riwayat_penanggung_jawab` (`id`, `kendaraan_id`, `pegawai_id`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(2, 2, 2, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(3, 4, 3, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(4, 5, 4, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(5, 7, 5, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(6, 8, 6, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(7, 9, 7, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(8, 11, 8, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(9, 12, 9, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(10, 13, 10, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(11, 14, 11, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(12, 15, 12, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(13, 16, 13, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(14, 18, 14, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(15, 19, 15, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03'),
(16, 20, 16, '2026-11-11 00:00:00', NULL, '2026-11-11 07:39:03', '2026-11-11 07:39:03');

SET FOREIGN_KEY_CHECKS = 1;
