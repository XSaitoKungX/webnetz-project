-- Create articles_db Database
CREATE DATABASE IF NOT EXISTS webnetz_db;
USE webnetz_db;

-- Table articles
CREATE TABLE IF NOT EXISTS articles
(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  title       VARCHAR(255) NOT NULL,
  description TEXT         NOT NULL,
  content     TEXT         NOT NULL,
  date        DATE         NOT NULL
);

-- Create table "categories"
CREATE TABLE IF NOT EXISTS categories
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  date        DATE         NOT NULL
);

-- Create table "login"
CREATE TABLE IF NOT EXISTS users
(
  id         INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(50)  NOT NULL,
  last_name  VARCHAR(50)  NOT NULL,
  email      VARCHAR(255) NOT NULL,
  username   VARCHAR(255) NOT NULL,
  password   VARCHAR(255) NOT NULL
);

-- Create table "comments"
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  article_id INT,
  email VARCHAR(255),
  comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  comment_text TEXT,
  FOREIGN KEY (article_id) REFERENCES articles(id)
);

-- Create table "tags"
CREATE TABLE tags (
  tag VARCHAR(255) UNIQUE
);

CREATE TABLE articles_tags (
   article_id INT,
   tag VARCHAR(255),
   UNIQUE KEY unique_tag_per_article (article_id, tag),
   FOREIGN KEY (article_id) REFERENCES articles(id),
   FOREIGN KEY (tag) REFERENCES tags(tag)
);

ALTER TABLE comments
  ADD COLUMN is_public BOOLEAN DEFAULT FALSE;

ALTER TABLE comments
  ADD name VARCHAR(255) NOT NULL;

ALTER DATABASE webnetz_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `categories`
  CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE users
  ADD last_change_date DATE,
  ADD last_editor_id INT,
  ADD active BOOLEAN,
  ADD logo VARCHAR(255);

-- Add is_admin
ALTER TABLE users
  ADD is_admin BOOLEAN DEFAULT 0;

-- Add password_reset
ALTER TABLE users
  ADD password_reset VARCHAR(255);

-- Add image & last_change_date & last_editor_id
ALTER TABLE articles
  ADD image VARCHAR(255) NOT NULL,
  ADD last_change_date DATE,
  ADD last_editor_id INT;

-- Add last_change_date & last_editor_id
ALTER TABLE categories
  ADD last_change_date DATE,
  ADD last_editor_id INT;

-- Add category_id field to articles table
ALTER TABLE articles
  ADD category_id INT;

-- Add foreign key constraint to category_id referencing categories table
ALTER TABLE articles
  ADD FOREIGN KEY (category_id) REFERENCES categories (id);

ALTER TABLE articles
  ADD FOREIGN KEY (last_editor_id) REFERENCES users (id);

-- Update category_id values in articles table to match category IDs
UPDATE articles
SET category_id = 1
WHERE id = 1;
-- Replace 1 with the appropriate category ID for each article
