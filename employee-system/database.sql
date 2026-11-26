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
