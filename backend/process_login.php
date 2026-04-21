<?php
// login file 

session_start();
require_once __DIR__ . "/connect.php";

// request from post only 
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Invalid request method."));
    exit();
}

// read all data 
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

// check if all data true
if ($email == "" || $password == "") {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Please enter email and password."));
    exit();
}

// check email is valid

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Please enter a valid email address."));
    exit();
}

// prepared data  
$sql = "SELECT user_id, full_name, email, password_hash FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Database prepare error."));
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// if email not found in table users
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Email not found."));
    exit();
}

$user = mysqli_fetch_assoc($result);

//   password_verify if  password == password_hash in database
if (!password_verify($password, $user["password_hash"])) {
    header("Location: ../auth_login.html?status=error&message=" . urlencode("Wrong password."));
    exit();
}

// save session for users
$_SESSION["user_id"] = $user["user_id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["email"] = $user["email"];

// if login success go to index.html
header("Location: ../home.html?status=success&message=" . urlencode("Login successful."));
exit();
?>
