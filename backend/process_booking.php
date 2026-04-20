<?php
// شرح تفصيلي: هذا الملف له وظيفتان فقط:
// 1) عند طلب action=get_services يرجع الخدمات بصيغة JSON للواجهة.
// 2) عند إرسال نموذج الحجز يقوم بحفظ البيانات داخل جدول bookings.

session_start();
require_once __DIR__ . "/connect.php";

// شرح تفصيلي: هذا الجزء مخصص لتحميل الخدمات داخل الصفحة الرئيسية باستخدام fetch من JavaScript.
if (isset($_GET["action"]) && $_GET["action"] == "get_services") {
    header("Content-Type: application/json; charset=utf-8");

    $services = array();
    $sql = "SELECT service_id, service_name_en, description_en, price, duration_minutes FROM services WHERE is_active = 1 ORDER BY service_id ASC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }

        echo json_encode(array(
            "status" => "success",
            "data" => $services,
            "message" => "Services loaded successfully."
        ));
    } else {
        echo json_encode(array(
            "status" => "error",
            "data" => array(),
            "message" => "Could not load services from database."
        ));
    }

    exit();
}

// شرح تفصيلي: لو الملف لم يستقبل POST في جزء الحجز, نرجع للصفحة الرئيسية برسالة خطأ.
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../index.html?status=error&message=" . urlencode("Invalid booking request.") . "#booking");
    exit();
}

// شرح تفصيلي: قراءة بيانات الحجز من الفورم.
$full_name = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
$car_model = isset($_POST["car_model"]) ? trim($_POST["car_model"]) : "";
$service_id = isset($_POST["service_id"]) ? intval($_POST["service_id"]) : 0;
$booking_date = isset($_POST["booking_date"]) ? $_POST["booking_date"] : "";
$booking_time = isset($_POST["booking_time"]) ? $_POST["booking_time"] : "";
$notes = isset($_POST["notes"]) ? trim($_POST["notes"]) : "";
$user_id = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : null;

// شرح تفصيلي: نتحقق من الحقول الأساسية قبل الحفظ.
if ($full_name == "" || $email == "" || $phone == "" || $car_model == "" || $service_id <= 0 || $booking_date == "" || $booking_time == "") {
    header("Location: ../index.html?status=error&message=" . urlencode("Please fill in all booking fields.") . "#booking");
    exit();
}

// شرح تفصيلي: نتحقق من صحة البريد الإلكتروني.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../index.html?status=error&message=" . urlencode("Please enter a valid email address.") . "#booking");
    exit();
}

// شرح تفصيلي: هذا التحقق يتأكد أن الخدمة المختارة موجودة فعلاً ومفعلة داخل جدول services.
$service_sql = "SELECT service_id FROM services WHERE service_id = ? AND is_active = 1 LIMIT 1";
$service_stmt = mysqli_prepare($conn, $service_sql);

if (!$service_stmt) {
    header("Location: ../index.html?status=error&message=" . urlencode("Database prepare error.") . "#booking");
    exit();
}

mysqli_stmt_bind_param($service_stmt, "i", $service_id);
mysqli_stmt_execute($service_stmt);
$service_result = mysqli_stmt_get_result($service_stmt);

if (!$service_result || mysqli_num_rows($service_result) == 0) {
    header("Location: ../index.html?status=error&message=" . urlencode("Selected service was not found.") . "#booking");
    exit();
}

// شرح تفصيلي: نستخدم نوعين من الإدخال حسب وجود مستخدم مسجل دخول أو لا.
if ($user_id === null) {
    $insert_sql = "INSERT INTO bookings (user_id, service_id, customer_name, customer_email, customer_phone, car_model, booking_date, booking_time, notes) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);

    if (!$insert_stmt) {
        header("Location: ../index.html?status=error&message=" . urlencode("Database insert prepare error.") . "#booking");
        exit();
    }

    mysqli_stmt_bind_param($insert_stmt, "isssssss", $service_id, $full_name, $email, $phone, $car_model, $booking_date, $booking_time, $notes);
} else {
    $insert_sql = "INSERT INTO bookings (user_id, service_id, customer_name, customer_email, customer_phone, car_model, booking_date, booking_time, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);

    if (!$insert_stmt) {
        header("Location: ../index.html?status=error&message=" . urlencode("Database insert prepare error.") . "#booking");
        exit();
    }

    mysqli_stmt_bind_param($insert_stmt, "iisssssss", $user_id, $service_id, $full_name, $email, $phone, $car_model, $booking_date, $booking_time, $notes);
}

// شرح تفصيلي: إذا تم التنفيذ بنجاح فهذا يعني أن الحجز تم حفظه داخل قاعدة البيانات.
if (mysqli_stmt_execute($insert_stmt)) {
    header("Location: ../index.html?status=success&message=" . urlencode("Booking saved successfully.") . "#booking");
    exit();
}

header("Location: ../index.html?status=error&message=" . urlencode("Booking failed.") . "#booking");
exit();
?>
