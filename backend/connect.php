<?php
//connect file
$host = "localhost";
$user = "root";
$pass = "";
$db = "shinehub_db";

// open conntion
$conn = mysqli_connect($host, $user, $pass, $db);

// if conntion failed stop
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
