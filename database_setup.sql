-- Database: `avian_emily`

CREATE DATABASE IF NOT EXISTS `avian_emily` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `avian_emily`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_a`
--

DROP TABLE IF EXISTS `table_a`;
CREATE TABLE `table_a` (
  `kode_toko_baru` bigint(20) UNSIGNED NOT NULL,
  `kode_toko_lama` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `table_a`
--

INSERT INTO `table_a` (`kode_toko_baru`, `kode_toko_lama`) VALUES
(1, 6),
(2, NULL),
(3, 7),
(4, 9),
(5, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_b`
--

DROP TABLE IF EXISTS `table_b`;
CREATE TABLE `table_b` (
  `kode_toko` bigint(20) UNSIGNED NOT NULL,
  `nominal_transaksi` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `table_b`
--

INSERT INTO `table_b` (`kode_toko`, `nominal_transaksi`) VALUES
(1, '1000.00'),
(2, '1000.00'),
(4, '1000.00'),
(6, '1000.00'),
(7, '1000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_c`
--

DROP TABLE IF EXISTS `table_c`;
CREATE TABLE `table_c` (
  `kode_toko` bigint(20) UNSIGNED NOT NULL,
  `area_sales` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `table_c`
--

INSERT INTO `table_c` (`kode_toko`, `area_sales`) VALUES
(1, 'A'),
(2, 'A'),
(3, 'A'),
(4, 'B'),
(5, 'B');

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_d`
--

DROP TABLE IF EXISTS `table_d`;
CREATE TABLE `table_d` (
  `kode_sales` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_sales` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `table_d`
--

INSERT INTO `table_d` (`kode_sales`, `nama_sales`) VALUES
('A1', 'Alpha'),
('A2', 'Blue'),
('A3', 'Charlie'),
('B1', 'Delta'),
('B2', 'Echo');


ALTER TABLE `table_a`
  ADD PRIMARY KEY (`kode_toko_baru`);


ALTER TABLE `table_b`
  ADD PRIMARY KEY (`kode_toko`);


ALTER TABLE `table_c`
  ADD PRIMARY KEY (`kode_toko`);


ALTER TABLE `table_d`
  ADD PRIMARY KEY (`kode_sales`);


ALTER TABLE `table_a`
  MODIFY `kode_toko_baru` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

COMMIT;