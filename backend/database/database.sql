CREATE DATABASE IF NOT EXISTS shinehub_db;
USE shinehub_db;

--  Table for 'users'
-- This table stores information about customers who register for the service.
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
   
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for 'services'
-- This table lists all the car wash services offered, with details in both English and Arabic.
CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name_en VARCHAR(120) NOT NULL UNIQUE,
    
    description_en TEXT NOT NULL,
   
    price DECIMAL(10,2) NOT NULL,
    duration_minutes INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for 'bookings'
-- This table records all customer appointments for car wash services.
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    service_id INT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(120) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    car_model VARCHAR(120) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    notes TEXT NULL,
    status ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    CONSTRAINT fk_booking_service FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
);

--  Table for 'employees'
-- This table stores information about the staff working at ShineHub.
CREATE TABLE IF NOT EXISTS employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    position VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    email VARCHAR(120) UNIQUE,
    hire_date DATE NOT NULL,
    salary DECIMAL(10,2),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--  Table for 'payroll'
-- This table tracks salary payments to employees.
CREATE TABLE IF NOT EXISTS payroll (
    payroll_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    pay_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    pay_period_start DATE,
    pay_period_end DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payroll_employee FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE
);

--  Table for 'inventory'
-- This table manages the stock of car wash products and supplies.
CREATE TABLE IF NOT EXISTS inventory (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    quantity INT NOT NULL DEFAULT 0,
    unit_price DECIMAL(10,2),
    supplier VARCHAR(120),
    last_restock_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Initial data for 'services' table
-- These are some default car wash services that ShineHub offers.
INSERT INTO services
(service_name_en,  description_en,  price, duration_minutes)
VALUES

('Exterior Wash','Car exterior cleaning',40,30),

('Interior Cleaning','Full interior cleaning',70,45),

('Polishing','Paint polishing service',150,60),

('Protection Coating','Paint protection layer',250,90),

('Deep Cleaning','Complete deep cleaning inside and outside',,200,90),

('Oil Change','Engine oil and filter replacement',180,40),

('Engine Check','Quick engine diagnostic check',60,20),

('Battery Check','Battery inspection service',35,15),

('Tire Service','Tire pressure check and inspection',25,15),

('Headlight Restoration','Restore headlight clarity',120,45),

('Engine Cleaning','Engine bay cleaning service',100,45),

('Wax Protection','Wax layer protection for paint',90,40),

('Full Detailing','Full car detailing service',400,180),

('Paint Correction','Remove minor scratches',300,120),

('Ceramic Coating','Long-term paint protection',1200,240)

ON DUPLICATE KEY UPDATE
price = VALUES(price),
duration_minutes = VALUES(duration_minutes),
description_en = VALUES(description_en),


