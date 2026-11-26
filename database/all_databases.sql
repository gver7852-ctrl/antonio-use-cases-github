-- All five databases. Import once on a fresh MySQL setup (phpMyAdmin > Import).

-- Case Study 1: Equipment Information System
CREATE DATABASE IF NOT EXISTS equipment_db;
USE equipment_db;

CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_name VARCHAR(150) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    storage_location VARCHAR(50) NOT NULL,
    quantity INT NOT NULL
);

-- Fictional sample records
INSERT INTO equipment (equipment_name, brand, storage_location, quantity) VALUES
('Digital Multimeter', 'TestLab', 'Laboratory 2', 3),
('Crimping Tool', 'NetPro', 'Network Room', 12),
('Soldering Station', 'HeatWorks', 'Laboratory 1', 0),
('LAN Cable Tester', 'NetPro', 'Network Room', 6);

-- Case Study 2: Activity Registration Information System
CREATE DATABASE IF NOT EXISTS activity_registration_db;
USE activity_registration_db;

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(30) NOT NULL,
    participant_name VARCHAR(100) NOT NULL,
    activity_name VARCHAR(100) NOT NULL,
    event_period VARCHAR(50) NOT NULL,
    status VARCHAR(20) NOT NULL
);

-- Fictional sample records
INSERT INTO registrations (registration_number, participant_name, activity_name, event_period, status) VALUES
('REG-001', 'Casey Ramos', 'Basic Photography', 'November 2026', 'Registered'),
('REG-002', 'Jamie Cruz', 'Community Dance Class', 'December 2026', 'Registered'),
('REG-003', 'Riley Santos', 'Urban Gardening', 'November 2026', 'Withdrawn'),
('REG-004', 'Morgan Lim', 'Basic Cooking', 'January 2027', 'Registered');

-- Case Study 3: Employee Information System
CREATE DATABASE IF NOT EXISTS employee_db;
USE employee_db;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_number VARCHAR(30) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    shift_number INT NOT NULL
);

-- Fictional sample records
INSERT INTO employees (employee_number, full_name, email, department, shift_number) VALUES
('EMP-001', 'Jordan Garcia', 'jordan@example.com', 'Customer Support', 1),
('EMP-002', 'Alex Reyes', 'alex@example.com', 'Accounting', 2),
('EMP-003', 'Sam Dela Cruz', 'sam@example.com', 'Warehouse', 3),
('EMP-004', 'Taylor Mendoza', 'taylor@example.com', 'Security', 4);

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
