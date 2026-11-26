-- Case Study 4: Vehicle Rental Information System
CREATE DATABASE IF NOT EXISTS vehicle_rental_db;
USE vehicle_rental_db;

CREATE TABLE rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    vehicle_plate VARCHAR(20) NOT NULL,
    rental_start DATE NOT NULL,
    return_date DATE NOT NULL
);

-- Fictional sample records
INSERT INTO rentals (customer_name, vehicle_plate, rental_start, return_date) VALUES
('Avery Tan', 'ABC-1234', '2026-11-10', '2026-11-12'),
('Quinn Bautista', 'XYZ-5821', '2026-11-18', '2026-11-21'),
('Drew Villanueva', 'NAK-3307', '2026-11-25', '2026-12-01'),
('Skyler Aquino', 'LMN-9046', '2026-12-03', '2026-12-04');
