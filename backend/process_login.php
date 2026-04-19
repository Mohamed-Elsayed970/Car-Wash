
<?php
// This file handles the user login process.
// It receives credentials from the login form, verifies them against the database,
// and if successful, starts a session for the user.

// Start a PHP session.
// This is crucial for maintaining user login state across different pages.
session_start();

// Include the database connection file.
// This gives us access to the $pdo object for database interactions.
require_once __DIR__ . "/connect.php";

// Function to send a JSON response back to the client.
// This helps the frontend understand the outcome of the login attempt.
function sendJsonResponse($status, $message, $redirect = null) {
    header("Content-Type: application/json");
    $response = ["status" => $status, "message" => $message];
    if ($redirect) {
        $response["redirect"] = $redirect;
    }
    echo json_encode($response);
    exit();
}

// Check if the request method is POST.
// Login forms should always use POST to send sensitive data securely.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get identity (username or email) and password from the login form.
    $identity = $_POST["identity"] ?? ""; // Can be either username or email
    $password = $_POST["password"] ?? "";

    // --- Basic Validation --- //
    // Check if both identity and password fields are filled.
    if (empty($identity) || empty($password)) {
        sendJsonResponse("error", "Please enter both your username/email and password.");
    }

    // --- Prepare and execute SQL query to find the user --- //
    // We need to check if a user exists with the given identity (email or username).
    try {
        // Prepare an SQL statement to select user data based on either email or username.
        // We use OR in the WHERE clause to allow login with both.
        $stmt = $pdo->prepare("SELECT user_id, full_name, email, password_hash FROM users WHERE email = :identity OR username = :identity");
        // Bind the identity value to the :identity placeholder.
        $stmt->bindParam(":identity", $identity);
        // Execute the query.
        $stmt->execute();
        // Fetch the user record.
        $user = $stmt->fetch();

        // If no user is found with that identity.
        if (!$user) {
            sendJsonResponse("error", "No account found with that email or username.");
        }

        // --- Verify the password --- //
        // password_verify() is the correct way to check a plain-text password against a hashed password.
        // It safely compares the provided password with the stored hash.
        if (password_verify($password, $user["password_hash"])) {
            // Password is correct! User is authenticated.

            // --- Start a session for the user --- //
            // Store essential user information in the session.
            // This information will be available on other pages as long as the session is active.
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["email"] = $user["email"];
            // You might also set a flag to indicate the user is logged in.
            $_SESSION["logged_in"] = true;

            // Send a success response. Optionally, tell the frontend where to redirect.
            sendJsonResponse("success", "Login successful! Welcome back.", "../pages/home.html");

        } else {
            // Password does not match.
            sendJsonResponse("error", "Incorrect password. Please try again.");
        }

    } catch (PDOException $e) {
        // Handle any database errors during the login process.
        sendJsonResponse("error", "Database error: " . $e->getMessage());
    }

} else {
    // If the request method is not POST, it's an invalid access.
    sendJsonResponse("error", "Invalid request method.");
}

?>
