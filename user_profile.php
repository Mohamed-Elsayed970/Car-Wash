<?php
// بدء الجلسة واستدعاء ملف الاتصال
session_start();
require_once "backend/connect.php";

// التأكد من أن المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: auth_login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. جلب بيانات المستخدم الأساسية
$user_sql = "SELECT full_name, email FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user_data = mysqli_fetch_assoc($user_result);

// 2. جلب الحجوزات الخاصة بالمستخدم مع اسم الخدمة
$bookings_sql = "SELECT b.*, s.service_name_en 
                 FROM bookings b 
                 JOIN services s ON b.service_id = s.service_id 
                 WHERE b.user_id = $user_id 
                 ORDER BY b.booking_date DESC";

$bookings_result = mysqli_query($conn, $bookings_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <head>
    <!-- شرح تفصيلي: هذه الصفحة هي الصفحة الرئيسية الجديدة index.html وهي بديلة عن home.html مع الحفاظ على نفس الاستايل العام. -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShineHub Car Wash</title>
    <meta name="description" content="Modern car wash management website with services, booking, and authentication." />
    <link rel="stylesheet" href="frontend/Stayl/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
</head>

<body>
    <div class="site-shell">
        <header class="header">
            <div class="container nav-bar">
                <a href="index.html" class="brand">
                    <span class="brand-mark">S</span>
                    <div>
                        <strong>ShineHub</strong>
                        <small>Car Wash & Detailing</small>
                    </div>
                </a>

                <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- شرح تفصيلي: كل روابط القائمة أصبحت تشير إلى الصفحات النهائية المطلوبة فقط. -->
                <nav class="nav" id="mainNav">
                    <a href="user_profile.php" class="nav-link">My Bookings</a>
                    <a href="home.html">Home</a>
                    <a href="About.html">About</a>
                    <a href="services.html">Services</a>
                    <a href="booking.html">Booking</a>
                    <!-- <a href="auth_login.html">Login</a> -->
                    <a href="contact.html">Contact</a>
                </nav>

                <div class="header-actions">
                    <!-- <a href="auth_login.html" class="btn btn-primary">login</a> -->
                    <a href="backend/process_logout.php" id="navLogout" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </header>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile - ShineHub</title>
    <link rel="stylesheet" href="frontend/Stayl/style.css" />
</head>
<body>
    <div class="site-shell">
        <div class="container section">
            
            <div class="form-card" style="margin-bottom: 2rem;">
                <h2 style="color: var(--primary);">User Information</h2>
                <p style="margin-top: 1rem;"><strong>Name:</strong> <?php echo $user_data['full_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
            </div>

            <div class="form-card">
                <h2 style="color: var(--primary); margin-bottom: 1.5rem;">My Bookings</h2>
                
                <table style="width: 100%; border-collapse: collapse; color: var(--text);">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--line); text-align: left;">
                            <th style="padding: 1rem;">Service</th>
                            <th style="padding: 1rem;">Car Model</th>
                            <th style="padding: 1rem;">Date</th>
                            <th style="padding: 1rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($bookings_result)): ?>
                            <tr style="border-bottom: 1px solid var(--line);">
                                <td style="padding: 1rem;"><?php echo $row['service_name_en']; ?></td>
                                <td style="padding: 1rem;"><?php echo $row['car_model']; ?></td>
                                <td style="padding: 1rem;"><?php echo $row['booking_date'] . " " . $row['booking_time']; ?></td>
                                <td style="padding: 1rem;">
                                    <span class="service-tag"><?php echo $row['status']; ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="padding: 2rem; text-align: center;">You have no bookings yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <footer class="footer">
            <div class="container footer-content">
                <p>© <span id="currentYear"></span> ShineHub. All rights reserved. Clean cars.</p>
            </div>
        </footer>

        </div>
    </div>
</body>
</html>