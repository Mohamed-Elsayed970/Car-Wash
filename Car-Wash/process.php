<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'car_wash_management'
];

$fallbackServices = [
    [
        'id' => 1,
        'service_name_en' => 'Exterior Wash',
        'service_name_ar' => 'غسيل خارجي',
        'description_en' => 'Quick foam wash, rinse, and drying for the outside body of the car.',
        'description_ar' => 'غسيل سريع بالرغوة ثم شطف وتجفيف للهيكل الخارجي للسيارة.',
        'price' => 15
    ],
    [
        'id' => 2,
        'service_name_en' => 'Interior Cleaning',
        'service_name_ar' => 'تنظيف داخلي',
        'description_en' => 'Vacuuming, dashboard cleaning, glass wiping, and floor mat care.',
        'description_ar' => 'تنظيف داخلي يشمل الشفط وتنظيف التابلوه والزجاج والعناية بالأرضيات.',
        'price' => 20
    ],
    [
        'id' => 3,
        'service_name_en' => 'Polishing',
        'service_name_ar' => 'تلميع',
        'description_en' => 'Paint polishing to restore gloss and improve the car appearance.',
        'description_ar' => 'تلميع للطلاء لاستعادة اللمعان وتحسين مظهر السيارة.',
        'price' => 35
    ],
    [
        'id' => 4,
        'service_name_en' => 'Protection Coating',
        'service_name_ar' => 'طبقة حماية',
        'description_en' => 'Adds a protective layer against dust, light dirt, and weather effects.',
        'description_ar' => 'إضافة طبقة حماية ضد الأتربة والأوساخ الخفيفة وتأثيرات الطقس.',
        'price' => 45
    ],
    [
        'id' => 5,
        'service_name_en' => 'Deep Cleaning',
        'service_name_ar' => 'تنظيف عميق',
        'description_en' => 'Complete interior and exterior detailing for a full refresh.',
        'description_ar' => 'تنظيف تفصيلي داخلي وخارجي كامل لاستعادة النظافة الكاملة.',
        'price' => 60
    ]
];

function jsonResponse(string $status, string $message, array $data = [], int $code = 200): void
{
    http_response_code($code);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function cleanInput(?string $value): string
{
    return trim((string) $value);
}

function connectDatabase(array $config): ?mysqli
{
    mysqli_report(MYSQLI_REPORT_OFF);

    $connection = @new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database']
    );

    if ($connection->connect_errno) {
        return null;
    }

    $connection->set_charset('utf8mb4');
    return $connection;
}

function generateUsername(mysqli $connection, string $fullName, string $email): string
{
    $base = preg_replace('/[^a-z0-9]+/i', '', strtolower(str_replace(' ', '', $fullName)));
    if ($base === '') {
        $base = preg_replace('/[^a-z0-9]+/i', '', strtolower(strtok($email, '@')));
    }
    if ($base === '') {
        $base = 'user';
    }

    $username = $base;
    $counter = 1;

    while (true) {
        $statement = $connection->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows === 0) {
            $statement->close();
            return $username;
        }

        $statement->close();
        $counter++;
        $username = $base . $counter;
    }
}

function fetchServices(?mysqli $connection, array $fallbackServices): array
{
    if (!$connection) {
        return $fallbackServices;
    }

    $result = $connection->query('SELECT id, service_name_en, service_name_ar, description_en, description_ar, price FROM services WHERE is_active = 1 ORDER BY id ASC');
    if (!$result) {
        return $fallbackServices;
    }

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    return $services ?: $fallbackServices;
}

$connection = connectDatabase($config);
$action = cleanInput($_GET['action'] ?? $_POST['action'] ?? '');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET' && $action === 'services') {
    jsonResponse('success', 'Services loaded successfully.', fetchServices($connection, $fallbackServices));
}

if ($method !== 'POST') {
    jsonResponse('error', 'Unsupported request method.', [], 405);
}

switch ($action) {
    case 'register':
        $fullName = cleanInput($_POST['full_name'] ?? '');
        $email = cleanInput($_POST['email'] ?? '');
        $phone = cleanInput($_POST['phone'] ?? '');
        $password = cleanInput($_POST['password'] ?? '');
        $confirmPassword = cleanInput($_POST['confirm_password'] ?? '');

        if ($fullName === '' || $email === '' || $phone === '' || $password === '' || $confirmPassword === '') {
            jsonResponse('error', 'Please fill in all registration fields.', [], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse('error', 'Please enter a valid email address.', [], 422);
        }

        if (strlen($password) < 6) {
            jsonResponse('error', 'Password must be at least 6 characters long.', [], 422);
        }

        if ($password !== $confirmPassword) {
            jsonResponse('error', 'Password confirmation does not match.', [], 422);
        }

        if (!$connection) {
            jsonResponse('error', 'Database connection failed. Import database.sql and update the database settings in process.php.', [], 500);
        }

        $checkUser = $connection->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $checkUser->bind_param('s', $email);
        $checkUser->execute();
        $existingUser = $checkUser->get_result();

        if ($existingUser->num_rows > 0) {
            $checkUser->close();
            jsonResponse('error', 'This email is already registered. Please login instead.', [], 409);
        }
        $checkUser->close();

        $username = generateUsername($connection, $fullName, $email);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $insertUser = $connection->prepare('INSERT INTO users (full_name, username, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)');
        $insertUser->bind_param('sssss', $fullName, $username, $email, $phone, $passwordHash);

        if (!$insertUser->execute()) {
            $insertUser->close();
            jsonResponse('error', 'Unable to create your account right now.', [], 500);
        }

        $insertUser->close();
        jsonResponse('success', 'Registration completed successfully. You can now login.', ['username' => $username]);
        break;

    case 'login':
        $identity = cleanInput($_POST['identity'] ?? '');
        $password = cleanInput($_POST['password'] ?? '');

        if ($identity === '' || $password === '') {
            jsonResponse('error', 'Please enter your login details.', [], 422);
        }

        if (!$connection) {
            jsonResponse('error', 'Database connection failed. Import database.sql and update the database settings in process.php.', [], 500);
        }

        $getUser = $connection->prepare('SELECT id, full_name, username, email, password_hash FROM users WHERE email = ? OR username = ? LIMIT 1');
        $getUser->bind_param('ss', $identity, $identity);
        $getUser->execute();
        $result = $getUser->get_result();
        $user = $result->fetch_assoc();
        $getUser->close();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            jsonResponse('error', 'Incorrect username, email, or password.', [], 401);
        }

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        jsonResponse('success', 'Login successful. Welcome back, ' . $user['full_name'] . '!', [
            'user' => [
                'id' => (int) $user['id'],
                'full_name' => $user['full_name'],
                'username' => $user['username'],
                'email' => $user['email']
            ]
        ]);
        break;

    case 'book_service':
        $fullName = cleanInput($_POST['full_name'] ?? '');
        $email = cleanInput($_POST['email'] ?? '');
        $phone = cleanInput($_POST['phone'] ?? '');
        $carModel = cleanInput($_POST['car_model'] ?? '');
        $serviceId = (int) ($_POST['service_id'] ?? 0);
        $bookingDate = cleanInput($_POST['booking_date'] ?? '');
        $bookingTime = cleanInput($_POST['booking_time'] ?? '');
        $notes = cleanInput($_POST['notes'] ?? '');

        if ($fullName === '' || $email === '' || $phone === '' || $carModel === '' || $serviceId <= 0 || $bookingDate === '' || $bookingTime === '') {
            jsonResponse('error', 'Please complete all required booking fields.', [], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse('error', 'Please enter a valid email address for your booking.', [], 422);
        }

        if ($bookingDate < date('Y-m-d')) {
            jsonResponse('error', 'Booking date cannot be in the past.', [], 422);
        }

        if (!$connection) {
            jsonResponse('error', 'Database connection failed. Import database.sql and update the database settings in process.php.', [], 500);
        }

        $serviceCheck = $connection->prepare('SELECT id, service_name_en FROM services WHERE id = ? AND is_active = 1 LIMIT 1');
        $serviceCheck->bind_param('i', $serviceId);
        $serviceCheck->execute();
        $serviceResult = $serviceCheck->get_result();
        $service = $serviceResult->fetch_assoc();
        $serviceCheck->close();

        if (!$service) {
            jsonResponse('error', 'Selected service was not found.', [], 404);
        }

        $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

        if ($userId === null) {
            $insertBooking = $connection->prepare('INSERT INTO bookings (user_id, service_id, customer_name, customer_email, customer_phone, car_model, booking_date, booking_time, notes) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)');
            $insertBooking->bind_param(
                'isssssss',
                $serviceId,
                $fullName,
                $email,
                $phone,
                $carModel,
                $bookingDate,
                $bookingTime,
                $notes
            );
        } else {
            $insertBooking = $connection->prepare('INSERT INTO bookings (user_id, service_id, customer_name, customer_email, customer_phone, car_model, booking_date, booking_time, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $insertBooking->bind_param(
                'iisssssss',
                $userId,
                $serviceId,
                $fullName,
                $email,
                $phone,
                $carModel,
                $bookingDate,
                $bookingTime,
                $notes
            );
        }

        if (!$insertBooking->execute()) {
            $insertBooking->close();
            jsonResponse('error', 'Unable to save your booking right now.', [], 500);
        }

        $bookingId = $insertBooking->insert_id;
        $insertBooking->close();

        jsonResponse('success', 'Booking submitted successfully. We will contact you soon to confirm the appointment.', [
            'booking_id' => $bookingId,
            'service' => $service['service_name_en']
        ]);
        break;

    default:
        jsonResponse('error', 'Invalid action.', [], 400);
}
