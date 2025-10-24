-- Libraries
CREATE TABLE IF NOT EXISTS `libraries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `city` VARCHAR(80) NOT NULL,
  `state` VARCHAR(80) NOT NULL,
  `created_at` TIMESTAMP DEFAULT 
  'cover_image' VARCHAR(255) NULL,
  CURRENT_TIMESTAMP,
  `map_url` VARCHAR(255) NULL
);

--Library Images
CREATE TABLE IF NOT EXISTS `library_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `library_id` INT NOT NULL,
    `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,
    `image_url` VARCHAR(255) NOT NULL,
    CONSTRAINT `fk_li_library` FOREIGN KEY (`library_id`) REFERENCES `libraries`(`id`) ON DELETE CASCADE
);

-- Books
CREATE TABLE IF NOT EXISTS `books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `author` VARCHAR(120) NOT NULL,
  `genre` VARCHAR(80) NULL,
  `isbn` VARCHAR(20) NULL,
  `description` TEXT NULL,
  `cover_image` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Book Images
CREATE TABLE IF NOT EXISTS `book_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `book_id` INT NOT NULL,
    `is_primary` BOOLEAN NOT NULL DEFAULT FALSE,
    `image_url` VARCHAR(255) NOT NULL,
    CONSTRAINT `fk_bi_book` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
);

-- Library-Books mapping
CREATE TABLE IF NOT EXISTS `library_books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `library_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `available_count` INT NOT NULL DEFAULT 0,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0,
  CONSTRAINT `fk_lb_library` FOREIGN KEY (`library_id`) REFERENCES `libraries`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lb_book` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
);

-- Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(160) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `location_city` VARCHAR(80) NULL,
  `location_state` VARCHAR(80) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(160) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `library_id` INT NOT NULL,
  `type` ENUM('borrow','purchase') NOT NULL,
  `status` VARCHAR(40) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_tr_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tr_book` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tr_library` FOREIGN KEY (`library_id`) REFERENCES `libraries`(`id`) ON DELETE CASCADE
);

-- Pre-seeded Admin (email: admin@bookxs.com, password: admin123)
INSERT INTO `admins` (`name`, `email`, `password_hash`) VALUES
('Administrator', 'admin@bookxs.com', '$2y$10$wtheSOQ9thf2nq4CDkTkI.EiETHkNxkUXbgQ/A3akFpinCZocGHq.');