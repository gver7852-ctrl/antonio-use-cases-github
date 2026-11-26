CREATE DATABASE IF NOT EXISTS equipment_db;
USE equipment_db;

CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_name VARCHAR(150) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    storage_location VARCHAR(50) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO equipment (equipment_name, brand, storage_location, quantity) VALUES
('Digital Multimeter', 'TestLab', 'Laboratory 2', 3),
('Crimping Tool', 'NetPro', 'Network Room', 12),
('Soldering Station', 'HeatWorks', 'Laboratory 1', 0),
('LAN Cable Tester', 'NetPro', 'Network Room', 6);
