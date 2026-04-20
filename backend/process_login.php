<?php
// شرح تفصيلي: هذا الملف مسؤول عن استقبال بيانات تسجيل الدخول من auth_login.html
// ثم التحقق من البريد وكلمة المرور داخل قاعدة البيانات.

session_start();
require_once __DIR__ . "/connect.php";

// شرح تفصيلي: لو أحد فتح الملف مباشرة من المتصفح بدون إرسال POST,
// نرجعه إلى صفحة تسجيل الدخول برسالة خطأ واضحة.
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Invalid request method."));
    exit();
}

// شرح تفصيلي: نقرأ البيانات القادمة من الفورم وننظف الفراغات الزائدة.
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

// شرح تفصيلي: لو المستخدم لم يكتب البريد أو كلمة المرور, نظهر رسالة خطأ ونرجعه لنفس الصفحة.
if ($email == "" || $password == "") {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Please enter email and password."));
    exit();
}

// شرح تفصيلي: نتحقق أولاً أن البريد الإلكتروني مكتوب بصيغة صحيحة.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Please enter a valid email address."));
    exit();
}

// شرح تفصيلي: نستخدم prepared statement حتى يكون الكود آمن ومنظم للمبتدئين.
$sql = "SELECT user_id, full_name, email, password_hash FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Database prepare error."));
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// شرح تفصيلي: لو البريد غير موجود أساساً في جدول users, نرجع برسالة مناسبة.
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Email not found."));
    exit();
}

$user = mysqli_fetch_assoc($result);

// شرح تفصيلي: password_verify تقارن كلمة المرور المدخلة مع النسخة المشفرة في قاعدة البيانات.
if (!password_verify($password, $user["password_hash"])) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Wrong password."));
    exit();
}

// شرح تفصيلي: بعد نجاح تسجيل الدخول نخزن بعض البيانات في session لاستخدامها لاحقاً.
$_SESSION["user_id"] = $user["user_id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["email"] = $user["email"];

// شرح تفصيلي: حسب المطلوب, النجاح يجب أن يحول المستخدم إلى index.html فقط.
header("Location: ../home.html?status=success&message=" . urlencode("Login successful."));
exit();
?>
