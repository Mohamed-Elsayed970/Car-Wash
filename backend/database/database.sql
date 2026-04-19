CREATE DATABASE IF NOT EXISTS shinehub_db;
USE shinehub_db;

--  Table for 'users'
-- This table stores information about customers who register for the service.
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    username VARCHAR(80) UNIQUE,
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
    service_name_ar VARCHAR(120) NOT NULL,
    description_en TEXT NOT NULL,
    description_ar TEXT NOT NULL,
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
INSERT INTO services (service_name_en, service_name_ar, description_en, description_ar, price, duration_minutes)
VALUES
    ('Exterior Wash', 'غسيل خارجي', 'Quick foam wash, rinse, and drying for the outside body of the car.', 'غسيل سريع بالرغوة ثم شطف وتجفيف للهيكل الخارجي للسيارة.', 15.00, 25),
    ('Interior Cleaning', 'تنظيف داخلي', 'Vacuuming, dashboard cleaning, glass wiping, and floor mat care.', 'تنظيف داخلي يشمل الشفط وتنظيف التابلوه والزجاج والعناية بالأرضيات.', 20.00, 35),
    ('Polishing', 'تلميع', 'Paint polishing to restore gloss and improve the car appearance.', 'تلميع للطلاء لاستعادة اللمعان وتحسين مظهر السيارة.', 35.00, 50),
    ('Protection Coating', 'طبقة حماية', 'Adds a protective layer against dust, light dirt, and weather effects.', 'إضافة طبقة حماية ضد الأتربة والأوساخ الخفيفة وتأثيرات الطقس.', 45.00, 60),
    ('Deep Cleaning', 'تنظيف عميق', 'Complete interior and exterior detailing for a full refresh.', 'تنظيف تفصيلي داخلي وخارجي كامل لاستعادة النظافة الكاملة.', 60.00, 90)
ON DUPLICATE KEY UPDATE
    price = VALUES(price),
    duration_minutes = VALUES(duration_minutes),
    description_en = VALUES(description_en),
    description_ar = VALUES(description_ar);
