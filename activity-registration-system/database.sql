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
