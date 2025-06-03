-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2025 pada 07.56
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
-- Database: `rating_sma`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `komentar` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`id`, `komentar`, `tanggal`) VALUES
(2, 'SMKN 3 Jayapura merupakan Sekolah Kejuruan terbaik di Jayapura', '2025-06-02 02:07:31'),
(8, 'SMA Katadah menjadi rekomendasi buat kalian yang baru lulus smp', '2025-06-02 12:17:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `sekolah_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `tanggal_rating` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rating`
--

INSERT INTO `rating` (`rating_id`, `sekolah_id`, `rating`, `tanggal_rating`) VALUES
(1, 3, 4, '2025-06-01 16:21:30'),
(2, 3, 4, '2025-06-01 16:21:44'),
(3, 3, 5, '2025-06-01 16:21:54'),
(8, 3, 5, '2025-06-02 03:13:44'),
(9, 3, 4, '2025-06-02 03:14:00'),
(11, 10, 2, '2025-06-02 03:22:27'),
(12, 11, 5, '2025-06-02 08:33:46'),
(13, 10, 3, '2025-06-03 00:12:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sekolah`
--

CREATE TABLE `sekolah` (
  `sekolah_id` int(11) NOT NULL,
  `nama_sekolah` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `akreditasi` enum('A','B','C','D') NOT NULL,
  `prestasi` text DEFAULT NULL,
  `lambang_sekolah` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sekolah`
--

INSERT INTO `sekolah` (`sekolah_id`, `nama_sekolah`, `alamat`, `akreditasi`, `prestasi`, `lambang_sekolah`) VALUES
(3, 'SMA KATADHA JAYAPURA', 'JL. RAYA ABEPURA KOTARAJA, Wai Mhorock, Kec. Abepura, Kota Jayapura, Papua 99357', 'A', 'Juara FUTSAL POCARI 2019\r\nJuara 1 Olimpiade Sains dan Teknologi UNCEN Tahun 2023\r\nJuara 3 Cerdas Cermat Sains dan Teknologi UNCEN Tahun 2023                                                        \r\nJuara 1 dan 3 Lomba KTI Tingkat SMA/MA Se- Kota/Kab Jayapura FMIPA UNCEN Tahun 2023\r\nSepak Bola : Juara 1 Rektor Uncen Cup ke-IV tahun 2024.                                                                 \r\nJuara 1 Lomba Paduan Suara Tingkat Kategori SMA/SMK/MA Tahun 2024.', '1.png'),
(10, 'SMA Negeri 4 Jayapura', 'JL. RAYA ABEPURA ENTROP, Distrik Jayapura Selatan, Kota Jayapura, Papua 99225', 'A', '58 siswa SMA Negeri 4 Jayapura berhasil lolos Seleksi Nasional Berdasarkan Prestasi (SNBP2024). \r\nJuara ketiga lomba peneliti belia tingkat nasional tahun 2024\r\nOlimpiade Olahraga Siswa Nasional (Puspresnas),Cabang Olahraga :\r\nKarate : Juara 1 Kumite Putra, Juara 3 Kumite Putra, Juara 1 Kata Putra, Juara 2 Kata Putra, Juara 3 Kata Putri.\r\nBulutangkis : Juara 2 Putra, Juara 2 dan 3 Putri.\r\nRenang : Juara 2 Putra, Juara 1 Putri.\r\nDuta SMA Nasional 2023\r\nJuara 1 IPB OSIS Festival 2023 dan meraih tiket emas\r\nJuara 1 dan 3 Walikota Speech Competition 2022', '2.png'),
(11, 'SMA Negeri 1 Jayapura', 'Jl.Biak Abepura, Jayapura, Papua 99351', 'A', 'Kejuaraan FLS2N & O2SN Tahun 2024. Bidang :                                                                   \r\nBaca Puisi : Juara 1 tingkat Kota dan Juara 3 tingkat Provinsi.\r\nCipta puisi : Juara 2 tingkat Kota.\r\nFilm Pendek : Juara 1 tingkat Kota & Provinsi.\r\nJurnalistik : Juara 3 tingkat Kota.\r\nMonolog : Juara 2 tingkat Kota & Juara 3 tingkat Kota.\r\nTari Kreasi : Juara 2 tingkat Kota.\r\nKomik Digital : Juara 2 tingkat Kota.\r\nFinalis Top 20 Duta GenRe Provinsi Papua\r\nFinalis Top 5 Duta GenRe Provinsi Papua \r\nJuara 1 Putra Duta GenRe Provinsi Papua \r\nJuara 1 pada event Honda DBL With Kopi Good Day 2025', '3.png'),
(12, 'SMAS YPPK Teruna Bakti Jayapura', 'JL. SPG WAENA, Yabansai, Kec. Heram, Kota Jayapura, Papua 99352', 'A', 'Juara 2 Karya Tulis Ilmiah Tingkat SMA/MA Se-Kota dan Kab, Jayapura Tahun 2023.                                                                \r\nJuara 1 Olimpiade Biologi Tingkat Nasional Provinsi Papua Tahun 2023.                                                       \r\nJuara 1 Lomba Debat Bahasa Tingkat Kota Jayapura Tahun 2023\r\nJuara 1 dan Juara 3 Tim Putra, Juara 3 Tim Putri dari Tournament 3x3 Basketball SMA Negeri Khusus Sains dan Bahasa Papua 2024.                                                                             Juara 2 Lomba Yospan PT/ SMA/SMK Se-Kota Jayapura Tahun 2024\r\nJuara 3 Olimpiade Olahraga Siswa Nasional Kategori Pencak Silat Kota Jayapura Tahun 2023', '4.png'),
(13, 'SMA GABUNGAN JAYAPURA', 'JL. SAMUDERA NO. 24 Jayapura, Mandala,Kec Jayapura Utara,Kota Jayapura Papua 99115', 'A', 'JUARA II ANGKAT BESI OLAHRAGA SISWA NASIONAL DI PALEMBANG.\r\nJUARA III BULU TANGKIS O2SN SMA TINGKAT NASIONAL\r\nLOMBA OSN INFORMATIKA TINGKAT KOTA JAYAPURA\r\nJUARA IV GERAK JALAN PUTRA, HUT KOTA JAYAPURA\r\nJUARA I RENANG PARALIMPIK TINGKAT NASIONAL', '5.png'),
(14, 'SMAN 5 Jayapura', 'JL.ANGKASAPURA BASE G, Kec.Jayapura Utara,Kota Jayapura, Papua 99113', 'A', 'Peringkat 1 lomba Yospan Tingkat Kota Tahun 2015\r\nPeringkat 3 Taekwondo Tingkat Provinsi Tahun 2013\r\nPeringkat 2 Taekwondo Tingkat Provinsi Tahun 2012\r\nPeringkat 1 Paduan Suara yang dilaksanakan oleh Universitas Cenderawasih Tahun 2019', '6.png'),
(15, 'SMAN 2 Jayapura', 'Jl. Serui No.16, Imbi, Jayapura Utara, Kota Jayapura, Papua 99116', 'A', 'Peringkat 2 nilai ujian Nasional tingkat kab/kota Tahun 2015\r\nPeringkat 3 Ulangan semester ll G tingkat Sekolah Tahun 2015\r\nPeringkat 1 lomba lukis Tingkat Kodam provinsi Tahun 2018\r\nPeringkat 3 Kejuaraan Nasional Karate Tingkat Nasional Tahun 2015\r\nJuara 1 bulu tangkis Olimpiade Siswa Nasional (O2SN) Tahun 2019\r\nJuara 1 My Pertamina Futsal Tahun 2024', '7.png'),
(16, 'SMA YPKP Diaspora Kotaraja', 'JL. KOTARAJA DALAM, Vim, Kec. Abepura, Kota Jayapura Prov. Papua 99225', 'A', 'Perlombaan lari peringkat 4 tingkat kab/kota tahun 2018 \r\nPertandingan sepak bola peringkat 3 tingkat kab/kota tahun 2018 \r\nPertandingan badminton peringkat 4 tingkat sekolah tahun 2017', '8.png'),
(17, 'SMA ADVENT KOTA JAYAPURA', 'JL. ARGAPURA II, Argapura, Kec. Jayapura Selatan, Kota Jayapura, Papua.', 'B', '', '9.png'),
(18, 'SMAS MANDALA TRIKORA', 'JL. DIPONEGORO NO. 14, Gurabesi, Kec. Jayapura Utara, Kota Jayapura Prov. Papua', 'A', 'Pencak silat peringkat 1 tingkat provinsi 2018 \r\nLomba gerak jalan HUT ke 106 peringkat ke-3 tingkat kab/kota 2016 \r\nKejuaraan bola voli putri HUT kota Jayapura peringkat 1 tingkat kab/kota 2016 \r\nBank papua cup peringkat 2 tingkat kab/kota 2016 \r\nMeraih perunggu di ajang bulutangkis BPK Sultra open 2021', '10.png'),
(19, 'SMAS MUHAMMADIYAH JAYAPURA', 'JL. ABEPANTAI NO.25 ABEPURA, Awiyo, Kec. Abepura, Kota Jayapura Prov. Papua 99351', 'A', 'Juara satu lomba menulis tingkat nasional', '11.png'),
(20, 'SMAN 6 SKOUW JAYAPURA', 'JL. RAYA PERBATASAN RI-PNG, KAMPUNG SKOUW MABO-DISTRIK MUARA TAMI KOTA JAYAPURA 99316', 'B', '', '12.png'),
(21, 'SMA Hikmah Yapis Jayapura', 'Jl. Dr. Sam Ratulangi No.3-A, Mandala, Kec. Jayapura Utara, Kota Jayapura, Papua', 'A', 'Juara umum kompetisi sepak bola liga Bupati\r\nJuara 2 lomba pidato tingkat SMA/SMK ke-Kota Jayapura\r\nJuara 3 Kemerdekaan \r\nJuara 1 Taekwondo', '14.png'),
(22, 'SMAN 3 JAYAPURA', 'JL. MERAH PUTIH BUPER WAENA, Waena, Kec. Heram, Kota Jayapura, Papua, dengan kode pos 99358.', 'A', '• Juara 2 lomba debat bahasa Inggris se-Papua \r\nJuara 2 dan juara harapan 1 lomba musikalisasi puisi dari balai bahasa Papua \r\nJuara 2 gerak jalan putri tingkat SMA 2023 \r\nJuara 2 lomba vlog 2003 \r\nJuara 1 lomba band cinta bangga paham  (CBP)2023 \r\nJuara 2 lomba rakit 1 genre Fellowship yang diadakan oleh BKKBN Provinsi Papua', '13.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `sekolah_id` (`sekolah_id`);

--
-- Indeks untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  ADD PRIMARY KEY (`sekolah_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `sekolah`
--
ALTER TABLE `sekolah`
  MODIFY `sekolah_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`sekolah_id`) REFERENCES `sekolah` (`sekolah_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
