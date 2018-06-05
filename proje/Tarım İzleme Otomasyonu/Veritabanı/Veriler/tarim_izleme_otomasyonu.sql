-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 26 Tem 2017, 12:27:05
-- Sunucu sürümü: 5.7.14
-- PHP Sürümü: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `tarim_izleme_otomasyonu`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cihazlar`
--

CREATE TABLE `cihazlar` (
  `id` int(11) NOT NULL,
  `mac_adresi` char(17) COLLATE utf8_turkish_ci NOT NULL,
  `tur_id` int(11) NOT NULL,
  `durum` tinyint(1) NOT NULL,
  `konum_kodu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `cihazlar`
--

INSERT INTO `cihazlar` (`id`, `mac_adresi`, `tur_id`, `durum`, `konum_kodu`) VALUES
(0, 'aaaaaaaaaaaaaaaaa', 0, 1, 0),
(1, 'bbbbbbbbbbbbbbbbb', 1, 1, 0),
(2, 'ccccccccccccccccc', 2, 1, 0),
(3, 'ddddddddddddddddd', 3, 1, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cihaz_konumlari`
--

CREATE TABLE `cihaz_konumlari` (
  `konum_kodu` int(11) NOT NULL,
  `konum_adi` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `aciklama` varchar(100) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `cihaz_konumlari`
--

INSERT INTO `cihaz_konumlari` (`konum_kodu`, `konum_adi`, `aciklama`) VALUES
(0, 'neverland', 'somewhere over there');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cihaz_turleri`
--

CREATE TABLE `cihaz_turleri` (
  `id` int(11) NOT NULL,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `cihaz_turleri`
--

INSERT INTO `cihaz_turleri` (`id`, `tur`) VALUES
(0, 'node 1'),
(1, 'node 2'),
(2, 'node 3'),
(3, 'node 4');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cihaz_verileri`
--

CREATE TABLE `cihaz_verileri` (
  `id` int(11) NOT NULL,
  `cihaz_id` int(11) NOT NULL,
  `sensor_id` int(11) NOT NULL,
  `sensor_turu_id` int(11) NOT NULL,
  `konum_kodu` int(11) NOT NULL,
  `data` float NOT NULL,
  `tarih_saat` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `kullanici_adi` varchar(10) COLLATE utf8_turkish_ci NOT NULL,
  `sifre` varchar(8) COLLATE utf8_turkish_ci NOT NULL,
  `ad` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `soyad` varchar(50) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sensorler`
--

CREATE TABLE `sensorler` (
  `id` int(11) NOT NULL,
  `tur_id` int(11) NOT NULL,
  `cihaz_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `sensorler`
--

INSERT INTO `sensorler` (`id`, `tur_id`, `cihaz_id`) VALUES
(0, 1, 0),
(1, 2, 1),
(2, 4, 1),
(3, 5, 1),
(4, 6, 1),
(5, 0, 2),
(6, 4, 2),
(7, 5, 2),
(8, 6, 2),
(9, 3, 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sensor_turleri`
--

CREATE TABLE `sensor_turleri` (
  `id` int(11) NOT NULL,
  `tur` varchar(50) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `sensor_turleri`
--

INSERT INTO `sensor_turleri` (`id`, `tur`) VALUES
(0, 'yağmur'),
(1, 'toprak nem'),
(2, 'gaz'),
(3, 'ışık'),
(4, 'nem'),
(5, 'sıcaklık'),
(6, 'ısı');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cihazlar`
--
ALTER TABLE `cihazlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cihazlar_tur_id` (`tur_id`),
  ADD KEY `fk_cihazlar_konum_kodu` (`konum_kodu`);

--
-- Tablo için indeksler `cihaz_konumlari`
--
ALTER TABLE `cihaz_konumlari`
  ADD PRIMARY KEY (`konum_kodu`);

--
-- Tablo için indeksler `cihaz_turleri`
--
ALTER TABLE `cihaz_turleri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cihaz_verileri`
--
ALTER TABLE `cihaz_verileri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_veriler_cihaz_id` (`cihaz_id`),
  ADD KEY `fk_veriler_sensor_id` (`sensor_id`),
  ADD KEY `fk_veriler_sensor_turu_id` (`sensor_turu_id`),
  ADD KEY `fk_veriler_konum_kodu` (`konum_kodu`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`kullanici_adi`);

--
-- Tablo için indeksler `sensorler`
--
ALTER TABLE `sensorler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sensorler_tur_id` (`tur_id`),
  ADD KEY `fk_sensorler_cihaz_id` (`cihaz_id`);

--
-- Tablo için indeksler `sensor_turleri`
--
ALTER TABLE `sensor_turleri`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `cihaz_verileri`
--
ALTER TABLE `cihaz_verileri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `cihazlar`
--
ALTER TABLE `cihazlar`
  ADD CONSTRAINT `fk_cihazlar_konum_kodu` FOREIGN KEY (`konum_kodu`) REFERENCES `cihaz_konumlari` (`konum_kodu`),
  ADD CONSTRAINT `fk_cihazlar_tur_id` FOREIGN KEY (`tur_id`) REFERENCES `cihaz_turleri` (`id`);

--
-- Tablo kısıtlamaları `cihaz_verileri`
--
ALTER TABLE `cihaz_verileri`
  ADD CONSTRAINT `fk_veriler_cihaz_id` FOREIGN KEY (`cihaz_id`) REFERENCES `cihazlar` (`id`),
  ADD CONSTRAINT `fk_veriler_konum_kodu` FOREIGN KEY (`konum_kodu`) REFERENCES `cihaz_konumlari` (`konum_kodu`),
  ADD CONSTRAINT `fk_veriler_sensor_id` FOREIGN KEY (`sensor_id`) REFERENCES `sensorler` (`id`),
  ADD CONSTRAINT `fk_veriler_sensor_turu_id` FOREIGN KEY (`sensor_turu_id`) REFERENCES `sensor_turleri` (`id`);

--
-- Tablo kısıtlamaları `sensorler`
--
ALTER TABLE `sensorler`
  ADD CONSTRAINT `fk_sensorler_cihaz_id` FOREIGN KEY (`cihaz_id`) REFERENCES `cihazlar` (`id`),
  ADD CONSTRAINT `fk_sensorler_tur_id` FOREIGN KEY (`tur_id`) REFERENCES `sensor_turleri` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
