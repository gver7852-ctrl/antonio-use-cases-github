-- Case Study 5: Service Information System
CREATE DATABASE IF NOT EXISTS service_db;
USE service_db;

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    fee DECIMAL(10,2) NOT NULL,
    available_slots INT NOT NULL
);

-- Fictional sample records
INSERT INTO services (service_name, category, fee, available_slots) VALUES
('Basic Haircut', 'Hair Care', '150.50', 20),
('Manicure', 'Nail Care', '120.00', 15),
('Hair Spa', 'Hair Care', '450.00', 8),
('Facial Cleaning', 'Skin Care', '350.75', 0);
