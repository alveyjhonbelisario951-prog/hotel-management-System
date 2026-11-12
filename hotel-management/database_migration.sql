-- Run once on an existing hotel_management database.
USE hotel_management;

ALTER TABLE rooms
    ADD COLUMN capacity TINYINT UNSIGNED NOT NULL DEFAULT 2 AFTER room_type,
    ADD COLUMN description VARCHAR(255) NOT NULL DEFAULT '' AFTER price_per_night;
