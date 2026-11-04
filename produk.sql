CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `produk` (`kode`, `nama`, `kategori`, `harga`, `stok`, `gambar`) VALUES
('ELK001', 'Laptop ASUS VivoBook', 'Laptop', 7500000, 10, 'laptop1.jpg'),
('ELK002', 'Samsung Galaxy S24', 'Smartphone', 12000000, 5, 'samsung.jpg'),
('ELK003', 'Logitech Mouse Wireless', 'Aksesoris', 150000, 50, 'mouse.jpg');