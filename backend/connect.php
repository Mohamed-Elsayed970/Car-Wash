<?php
// الملف ده مسؤول عن الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$pass = "";
$db = "shinehub_db";

// فتح الاتصال
$conn = mysqli_connect($host, $user, $pass, $db);

// لو الاتصال فشل نوقف التنفيذ
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ضبط الترميز علشان العربي والإنجليزي يشتغلوا بشكل طبيعي
mysqli_set_charset($conn, "utf8mb4");
?>
