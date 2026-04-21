<?php
// create now account

require_once __DIR__ . "/connect.php";

// request from post only 
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Invalid request method."));
    exit();
}

// read all data 
$full_name = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$confirm_password = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";

// check if all data true
if ($full_name == "" || $email == "" || $phone == "" || $password == "" || $confirm_password == "") {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Please fill in all fields.") . "&tab=register");
    exit();
}

// check email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Please enter a valid email address.") . "&tab=register");
    exit();
}

// length of the password
if (strlen($password) < 6) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Password must be at least 6 characters.") . "&tab=register");
    exit();
}

// check if pass == confirm
if ($password != $confirm_password) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Passwords do not match.") . "&tab=register");
    exit();
}

// check if email is already registered
$check_sql = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Database prepare error.") . "&tab=register");
    exit();
}

mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if ($check_result && mysqli_num_rows($check_result) > 0) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("This email is already registered.") . "&tab=register");
    exit();
}

// set pass into password_hash befor saved in database
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// add all data in table users
$insert_sql = "INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($conn, $insert_sql);

if (!$insert_stmt) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Database insert prepare error.") . "&tab=register");
    exit();
}

mysqli_stmt_bind_param($insert_stmt, "ssss", $full_name, $email, $phone, $password_hash);

if (mysqli_stmt_execute($insert_stmt)) {
    header("Location: ../auth_login.html?status=success&message=" . urlencode("Account created successfully. You can login now."));
    exit();
}

header("Location: ../auth_login.html?status=error&message=" . urlencode("Register failed.") . "&tab=register");
exit();
?>
