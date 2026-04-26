CREATE DATABASE shinehub_db;


USE shinehub_db;
if object_id('users','u') is not null drop table users;
if object_id('bookings','u') is not null drop table bookings;
if object_id('services','u') is not null drop table services;
if object_id('employees','u') is not null drop table employees;
if object_id('payroll','u') is not null drop table payroll;
if object_id('inventory','u') is not null drop table inventory;
go

CREATE TABLE users (
    user_id INT IDENTITY(1,1) PRIMARY KEY,
    full_name NVARCHAR(255) NOT NULL,
    email NVARCHAR(255) NOT NULL UNIQUE,
    phone NVARCHAR(50) NOT NULL,
    password_hash NVARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT GETDATE()
);

CREATE TABLE services (
    service_id INT IDENTITY(1,1) PRIMARY KEY,
    service_name_en NVARCHAR(255) NOT NULL UNIQUE,
    description_en NVARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration_minutes INT NOT NULL,
    is_active BIT DEFAULT 1,
    created_at DATETIME DEFAULT GETDATE()
);


CREATE TABLE bookings (
    booking_id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NULL,
    service_id INT NOT NULL,
    customer_name NVARCHAR(255) NOT NULL,
    customer_email NVARCHAR(255) NOT NULL,
    customer_phone NVARCHAR(50) NOT NULL,
    car_model NVARCHAR(255) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    notes NVARCHAR(255) NULL,
    status NVARCHAR(50) DEFAULT 'Pending',
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id)
);


CREATE TABLE employees (
    employee_id INT IDENTITY(1,1) PRIMARY KEY,
    full_name NVARCHAR(255) NOT NULL,
    position NVARCHAR(255) NOT NULL,
    phone NVARCHAR(50) NULL,
    email NVARCHAR(255) NULL,
    hire_date DATE NOT NULL,
    salary DECIMAL(10,2) NULL,
    is_active BIT DEFAULT 1,
    created_at DATETIME DEFAULT GETDATE()
);


CREATE TABLE payroll (
    payroll_id INT IDENTITY(1,1) PRIMARY KEY,
    employee_id INT NOT NULL,
    pay_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    pay_period_start DATE NULL,
    pay_period_end DATE NULL,
    notes NVARCHAR(255) NULL,
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);


CREATE TABLE inventory (
    item_id INT IDENTITY(1,1) PRIMARY KEY,
    item_name NVARCHAR(255) NOT NULL UNIQUE,
    description NVARCHAR(255) NULL,
    quantity INT DEFAULT 0,
    unit_price DECIMAL(10,2) NULL,
    supplier NVARCHAR(255) NULL,
    last_restock_date DATE NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);


INSERT INTO services (service_name_en, description_en, price, duration_minutes, is_active)
VALUES
('Exterior Wash', 'Car exterior cleaning', 40, 30, 1),
('Interior Cleaning', 'Full interior cleaning', 70, 45, 1),
('Polishing', 'Paint polishing service', 150, 60, 1),
('Protection Coating', 'Paint protection layer', 250, 90, 1),
('Deep Cleaning', 'Complete deep cleaning inside and outside', 200, 90, 1),
('Oil Change', 'Engine oil and filter replacement', 180, 40, 1),
('Engine Check', 'Quick engine diagnostic check', 60, 20, 1),
('Battery Check', 'Battery inspection service', 35, 15, 1),
('Tire Service', 'Tire pressure check and inspection', 25, 15, 1),
('Headlight Restoration', 'Restore headlight clarity', 120, 45, 1),
('Engine Cleaning', 'Engine bay cleaning service', 100, 45, 1),
('Wax Protection', 'Wax layer protection for paint', 90, 40, 1),
('Full Detailing', 'Full car detailing service', 400, 180, 1),
('Paint Correction', 'Remove minor scratches', 300, 120, 1),
('Ceramic Coating', 'Long-term paint protection', 1200, 240, 1);

