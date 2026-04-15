CREATE DATABASE IF NOT EXISTS car_wash_management;
USE car_wash_management;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    username VARCHAR(80) UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name_en VARCHAR(120) NOT NULL UNIQUE,
    service_name_ar VARCHAR(120) NOT NULL,
    description_en TEXT NOT NULL,
    description_ar TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration_minutes INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_booking_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

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
