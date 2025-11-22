-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Nov 2025 pada 14.34
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipps`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending_payment','paid','verified','in_production','ready_to_ship','shipped','completed','cancelled','return_requested','returned') NOT NULL DEFAULT 'pending_payment',
  `subtotal` decimal(12,2) NOT NULL,
  `ongkir` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_harga` decimal(12,2) NOT NULL,
  `total_item` int(11) NOT NULL,
  `berat_total` int(11) NOT NULL DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `metode_pembayaran` enum('credit_card','bank_transfer','gopay','shopeepay','qris','alfamart','indomaret','cod') DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','settlement','capture','deny','cancel','expire','failure') NOT NULL DEFAULT 'pending',
  `payment_expired_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `tipe_pengiriman` enum('dalam_kota','antar_kota') DEFAULT NULL,
  `kurir` varchar(255) DEFAULT NULL,
  `service_kurir` varchar(255) DEFAULT NULL,
  `resi` varchar(255) DEFAULT NULL,
  `estimasi_pengiriman` varchar(100) DEFAULT NULL,
  `status_pengiriman` enum('pending','picked_up','in_transit','delivered','returned') NOT NULL DEFAULT 'pending',
  `penerima_nama` varchar(255) NOT NULL,
  `penerima_telepon` varchar(255) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `kelurahan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kota` varchar(255) NOT NULL,
  `kota_id` bigint(20) UNSIGNED DEFAULT NULL,
  `provinsi` varchar(255) NOT NULL DEFAULT 'Gorontalo',
  `provinsi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `province_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subdistrict_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `status`, `subtotal`, `ongkir`, `total_harga`, `total_item`, `berat_total`, `catatan`, `metode_pembayaran`, `snap_token`, `transaction_id`, `payment_status`, `payment_expired_at`, `paid_at`, `tipe_pengiriman`, `kurir`, `service_kurir`, `resi`, `estimasi_pengiriman`, `status_pengiriman`, `penerima_nama`, `penerima_telepon`, `alamat_lengkap`, `kelurahan`, `kecamatan`, `kota`, `kota_id`, `provinsi`, `provinsi_id`, `province_id`, `city_id`, `district_id`, `subdistrict_id`, `kode_pos`, `verified_at`, `shipped_at`, `completed_at`, `cancelled_at`, `cancel_reason`, `created_at`, `updated_at`) VALUES
(3, 'ORD-20251120-0001', 7, 'pending_payment', 110000.00, 125000.00, 235000.00, 1, 360, NULL, NULL, 'b7663fb7-5866-47b1-ab2d-4adf47beaae2', NULL, 'pending', '2025-11-21 05:31:10', NULL, 'antar_kota', 'Satria Antaran Prima', 'UDRONS', NULL, '1-2 day', 'pending', 'Fiqri Peazzy', '082192081004', 'Jl Dulohupa 1, Dulomo', '', 'PALELEH', 'BUOL', 504, 'SULAWESI TENGAH', 27, 27, 504, 4815, NULL, '8888', NULL, NULL, NULL, NULL, NULL, '2025-11-20 05:20:01', '2025-11-20 05:31:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_transaction_id_index` (`transaction_id`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_payment_status_index` (`payment_status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
