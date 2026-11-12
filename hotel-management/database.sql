CREATE DATABASE IF NOT EXISTS hotel_management;
USE hotel_management;

CREATE TABLE IF NOT EXISTS rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type VARCHAR(30) NOT NULL,
    capacity TINYINT UNSIGNED NOT NULL DEFAULT 2,
    price_per_night DECIMAL(10,2) NOT NULL,
    description VARCHAR(255) NOT NULL DEFAULT '',
    status ENUM('Available','Occupied','Under Maintenance') NOT NULL DEFAULT 'Available'
);

INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, status) VALUES
('101', 'Single', 1, 1500.00, '', 'Available'),
('102', 'Deluxe', 2, 2500.00, '', 'Occupied'),
('201', 'Double', 2, 2000.00, '', 'Available'),
('301', 'Suite',  4, 4500.00, '', 'Under Maintenance');
