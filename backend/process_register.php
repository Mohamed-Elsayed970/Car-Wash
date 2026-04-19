
<?php
// This file handles the registration process for new users.
// It receives data from the registration form, validates it, hashes the password,
// and then saves the new user's information into the database.

// Start a PHP session. Sessions are used to store information (variables)
// across multiple pages. For example, after a user logs in, we might store their
// user ID in a session to keep them logged in as they browse the site.
session_start();

// Include the database connection file.
// This line brings in the code from connect.php, which establishes a connection
// to our MySQL database using PDO. We need this connection to save user data.
require_once __DIR__ . "/connect.php";

// Function to send a JSON response back to the client (web browser).
// This is useful for AJAX requests where the frontend expects a structured response.
function sendJsonResponse($status, $message) {
    // Set the HTTP header to indicate that the response is JSON.
    header("Content-Type: application/json");
    // Encode the status and message into a JSON string and print it.
    echo json_encode(["status" => $status, "message" => $message]);
    // Stop script execution after sending the response.
    exit();
}

// Check if the request method is POST.
// Registration forms typically send data using the POST method to keep sensitive
// information (like passwords) out of the URL.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the registration form.
    // We use the null coalescing operator (?? "") to ensure that if a POST variable
    // is not set, it defaults to an empty string, preventing PHP notices.
    $full_name = $_POST["full_name"] ?? "";
    $email = $_POST["email"] ?? "";
    $phone = $_POST["phone"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // --- Basic Validation --- //
    // Check if all required fields are filled.
    if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        // If any field is empty, send an error response.
        sendJsonResponse("error", "Please fill in all required fields.");
    }

    // Check if the password and confirm password match.
    if ($password !== $confirm_password) {
        // If they don't match, send an error response.
        sendJsonResponse("error", "Passwords do not match.");
    }

    // Check if the password is long enough (e.g., at least 6 characters).
    if (strlen($password) < 6) {
        // If the password is too short, send an error response.
        sendJsonResponse("error", "Password must be at least 6 characters long.");
    }

    // Validate email format.
    // filter_var() is a PHP function used to validate and sanitize data.
    // FILTER_VALIDATE_EMAIL checks if the email address is in a valid format.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If the email format is invalid, send an error response.
        sendJsonResponse("error", "Invalid email format.");
    }

    // --- Check if email already exists --- //
    // We need to make sure that each user has a unique email address.
    try {
        // Prepare an SQL statement to select a user by their email.
        // Using a prepared statement helps prevent SQL injection attacks.
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        // Bind the email value to the :email placeholder.
        $stmt->bindParam(":email", $email);
        // Execute the prepared statement.
        $stmt->execute();
        // Check if any row was returned. If yes, the email already exists.
        if ($stmt->rowCount() > 0) {
            // If the email is already registered, send an error response.
            sendJsonResponse("error", "This email is already registered. Please try logging in or use a different email.");
        }
    } catch (PDOException $e) {
        // If there's a database error during the check, send an error response.
        sendJsonResponse("error", "Database error: " . $e->getMessage());
    }

    // --- Hash the password --- //
    // IMPORTANT: Never store passwords in plain text in your database!
    // password_hash() creates a strong, one-way hash of the password.
    // This means we can verify a password later without ever knowing the original password.
    // PASSWORD_DEFAULT tells PHP to use the strongest hashing algorithm available (currently bcrypt).
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // --- Insert new user into the database --- //
    try {
        // Prepare an SQL INSERT statement to add the new user's details.
        $stmt = $pdo->prepare(
            "INSERT INTO users (full_name, email, phone, password_hash) VALUES (:full_name, :email, :phone, :password_hash)"
        );
        // Bind the values from our form to the placeholders in the SQL statement.
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":password_hash", $password_hashed);
        // Execute the statement to save the new user.
        $stmt->execute();

        // If the insertion is successful, send a success response.
        sendJsonResponse("success", "Registration successful! You can now log in.");

    } catch (PDOException $e) {
        // If there's a database error during insertion, send an error response.
        sendJsonResponse("error", "Registration failed: " . $e->getMessage());
    }

} else {
    // If the request method is not POST, it means someone tried to access this file directly
    // or used a different method. We should not process it.
    sendJsonResponse("error", "Invalid request method.");
}

?>
?>
