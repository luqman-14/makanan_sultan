CREATE DATABASE IF NOT EXISTS `kuliner_sultan`;
USE `kuliner_sultan`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(190) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `price` INT NOT NULL,
    `rating` DECIMAL(2,1) DEFAULT 4.9,
    `description` TEXT,
    `image_url` VARCHAR(255),
    `is_available` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(30) NOT NULL,
    `address` TEXT NOT NULL,
    `items_json` TEXT NOT NULL,
    `total_price` INT NOT NULL,
    `status` ENUM('Pending', 'Diproses', 'Selesai', 'Dibatalkan') DEFAULT 'Pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `products` (`name`, `category`, `price`, `rating`, `description`, `image_url`) VALUES
('Risol Mayo Smoked Beef Lumer', 'risol', 18000, 4.9, 'Risol gurih isi smoked beef premium, telur, dan racikan keju mayones melimpah.', 'https://images.unsplash.com/photo-1541529086526-db283c563270?auto=format&fit=crop&w=600&q=80'),
('Tahu Kocek Rawit Setan (Porsi Jumbo)', 'tahu', 15000, 4.8, 'Tahu goreng krispi dikocek dengan ulekan cabai rawit merah asli dan bumbu gurih sultan.', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80'),
('Es Teler Sultan Durian Musang King', 'esteler', 25000, 5.0, 'Es teler racikan krimer premium dengan nangka, kelapa muda, alpukat, dan toping durian asli.', 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=600&q=80'),
('Dimsum Mentai Flame-Torched (4 pcs)', 'dimsum', 22000, 4.9, 'Dimsum ayam udang tebal disiram saus mentai gurih pedas dan dibakar dengan torch.', 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=600&q=80'),
('Paket Frozen Risol Mayo (Isi 10)', 'frozen', 45000, 4.9, 'Paket mentah siap goreng kapan saja di rumah. Tahan 1 bulan di freezer.', 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80'),
('Combo Sultan Kenyang (Risol + Dimsum + Es Teler)', 'combo', 55000, 5.0, 'Paket komplit 1 Risol Mayo + 1 Dimsum Mentai + 1 Es Teler Durian.', 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80');
