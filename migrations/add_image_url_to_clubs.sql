-- Migration: add image_url to clubs and helper instructions
-- Run this SQL (mysql client or phpMyAdmin) to add the column.
-- MySQL 8+ supports IF NOT EXISTS for ADD COLUMN; older versions may error if column exists.

ALTER TABLE `clubs`
  ADD COLUMN IF NOT EXISTS `image_url` VARCHAR(255) DEFAULT NULL COMMENT 'Hình ảnh CLB (đường dẫn)';

-- The SQL below is a placeholder; transliteration (remove diacritics) is easier in PHP than SQL.
-- We recommend running the provided PHP helper at admin/tools/populate_club_images.php
-- which will compute a slug from the `city` (or `name`) and set image_url = 'img/clb-{slug}.png'

-- Example manual update (ONLY if you have slugs already):
-- UPDATE `clubs` SET image_url = CONCAT('img/clb-', LOWER(REPLACE(city, ' ', '-')), '.png') WHERE image_url IS NULL;

-- After running migration, optionally verify:
-- SELECT id, name, city, image_url FROM clubs LIMIT 50;
