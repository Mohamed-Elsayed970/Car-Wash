

DROP DATABASE IF EXISTS car_wash_db;
CREATE DATABASE car_wash_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE car_wash_db;


CREATE TABLE customer (
    customer_id       INT            NOT NULL AUTO_INCREMENT,
    name              VARCHAR(100)   NOT NULL,
    email             VARCHAR(100)   NOT NULL,
    phone             VARCHAR(20)    NOT NULL,
    registration_date DATE           NOT NULL DEFAULT (CURRENT_DATE),

    CONSTRAINT pk_customer PRIMARY KEY (customer_id),
    CONSTRAINT uq_customer_email UNIQUE (email)
) ENGINE=InnoDB;


CREATE TABLE vehicle (
    vehicle_id    INT           NOT NULL AUTO_INCREMENT,
    customer_id   INT           NOT NULL,
    license_plate VARCHAR(20)   NOT NULL,
    model         VARCHAR(100)  NOT NULL,
    color         VARCHAR(30)   NOT NULL,

    CONSTRAINT pk_vehicle PRIMARY KEY (vehicle_id),
    CONSTRAINT uq_vehicle_plate UNIQUE (license_plate),
    CONSTRAINT fk_vehicle_customer
        FOREIGN KEY (customer_id)
        REFERENCES customer (customer_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    INDEX idx_vehicle_customer_id (customer_id)
) ENGINE=InnoDB;


CREATE TABLE service (
    service_id       INT            NOT NULL AUTO_INCREMENT,
    name             VARCHAR(100)   NOT NULL,
    price            DECIMAL(8, 2)  NOT NULL,
    duration_minutes INT            NOT NULL,

    CONSTRAINT pk_service  PRIMARY KEY (service_id),
    CONSTRAINT chk_price   CHECK (price >= 0),
    CONSTRAINT chk_dur     CHECK (duration_minutes > 0)
) ENGINE=InnoDB;



CREATE TABLE employee (
    employee_id INT           NOT NULL AUTO_INCREMENT,
    name        VARCHAR(100)  NOT NULL,
    phone       VARCHAR(20)   NOT NULL,
    role        VARCHAR(50)   NOT NULL,

    CONSTRAINT pk_employee PRIMARY KEY (employee_id)
) ENGINE=InnoDB;



CREATE TABLE booking (
    booking_id   INT            NOT NULL AUTO_INCREMENT,
    vehicle_id   INT            NOT NULL,
    service_id   INT            NOT NULL,
    employee_id  INT            NOT NULL,
    booking_date DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status       ENUM(
                     'pending',
                     'confirmed',
                     'completed',
                     'cancelled'
                 )              NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(10,2)  NOT NULL,

    CONSTRAINT pk_booking PRIMARY KEY (booking_id),
    CONSTRAINT chk_total  CHECK (total_amount >= 0),

    CONSTRAINT fk_booking_vehicle
        FOREIGN KEY (vehicle_id)
        REFERENCES vehicle (vehicle_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_booking_service
        FOREIGN KEY (service_id)
        REFERENCES service (service_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_booking_employee
        FOREIGN KEY (employee_id)
        REFERENCES employee (employee_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_booking_vehicle_id  (vehicle_id),
    INDEX idx_booking_service_id  (service_id),
    INDEX idx_booking_employee_id (employee_id)
) ENGINE=InnoDB;



CREATE TABLE payment (
    payment_id INT            NOT NULL AUTO_INCREMENT,
    booking_id INT            NOT NULL,
    amount     DECIMAL(10,2)  NOT NULL,
    method     ENUM(
                   'cash',
                   'credit_card',
                   'debit_card',
                   'online_transfer',
                   'mobile_wallet'
               )              NOT NULL,
    paid_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_payment PRIMARY KEY (payment_id),
    CONSTRAINT uq_payment_booking UNIQUE (booking_id),   -- enforces strict 1:1
    CONSTRAINT chk_amount CHECK (amount >= 0),

    CONSTRAINT fk_payment_booking
        FOREIGN KEY (booking_id)
        REFERENCES booking (booking_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_payment_booking_id (booking_id)
) ENGINE=InnoDB;




INSERT INTO customer (name, email, phone, registration_date) VALUES
    ('Ahmed Ali',      'ahmed@mail.com',   '0100-1111111', '2024-01-15'),
    ('Mona Samir',     'mona@mail.com',    '0101-2222222', '2024-02-20'),
    ('Youssef Tarek',  'youssef@mail.com', '0102-3333333', '2024-03-05');

INSERT INTO vehicle (customer_id, license_plate, model, color) VALUES
    (1, 'ABC-1234', 'Toyota Corolla 2020', 'White'),
    (1, 'DEF-5678', 'Honda CR-V 2019',    'Black'),
    (2, 'GHI-9012', 'Hyundai Elantra 2021','Silver'),
    (3, 'JKL-3456', 'Ford F-150 2018',    'Blue');

INSERT INTO service (name, price, duration_minutes) VALUES
    ('Basic Wash',    50.00,  20),
    ('Full Wash',    120.00,  45),
    ('Premium Detail',250.00, 90),
    ('Interior Vacuum',80.00, 30),
    ('Tire Shine',    40.00,  15);

INSERT INTO employee (name, phone, role) VALUES
    ('Omar Hassan',  '0111-4444444', 'Washer'),
    ('Sara Ahmed',   '0122-5555555', 'Supervisor'),
    ('Karim Nasser', '0133-6666666', 'Washer');

INSERT INTO booking (vehicle_id, service_id, employee_id, booking_date, status, total_amount) VALUES
    (1, 1, 1, '2025-04-10 09:00:00', 'confirmed',  50.00),
    (2, 2, 2, '2025-04-08 11:00:00', 'completed', 120.00),
    (3, 1, 1, '2025-04-12 14:00:00', 'pending',    50.00),
    (4, 3, 3, '2025-04-11 10:30:00', 'completed', 250.00);

INSERT INTO payment (booking_id, amount, method, paid_at) VALUES
    (2, 120.00, 'credit_card',     '2025-04-08 12:15:00'),
    (4, 250.00, 'cash',            '2025-04-11 11:45:00');



CREATE OR REPLACE VIEW vw_booking_summary AS
SELECT
    b.booking_id,
    c.name            AS customer_name,
    c.phone           AS customer_phone,
    v.license_plate,
    v.model,
    s.name            AS service_name,
    e.name            AS employee_name,
    b.booking_date,
    b.status,
    b.total_amount,
    p.method          AS payment_method,
    p.paid_at
FROM booking b
JOIN vehicle  v ON b.vehicle_id  = v.vehicle_id
JOIN customer c ON v.customer_id = c.customer_id
JOIN service  s ON b.service_id  = s.service_id
JOIN employee e ON b.employee_id = e.employee_id
LEFT JOIN payment p ON b.booking_id = p.booking_id;

