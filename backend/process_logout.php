<?php
// شرح تفصيلي: هذا الملف لتسجيل الخروج وإنهاء الجلسة
session_start();
session_unset(); // تفريغ البيانات
session_destroy(); // تدمير الجلسة تماماً

// تحويل لصفحة البداية مع رسالة نجاح
header("Location: ../index.html?status=success&message=" . urlencode("Logged out successfully."));
exit();
?>