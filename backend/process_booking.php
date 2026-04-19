
<?php
// This file handles the booking process for car wash services.
// It receives booking details from the form, validates them, and saves the booking
// into the database. It also provides a way to fetch available services for the dropdown.

// Start a PHP session.
// We use sessions to check if a user is logged in and to potentially link bookings
// to their user account.
session_start();

// Include the database connection file.
// This gives us access to the $pdo object for database interactions.
require_once __DIR__ . "/connect.php";

// Function to send a JSON response back to the client.
// This is used for AJAX requests from the frontend to provide feedback.
function sendJsonResponse($status, $message, $data = null) {
    header("Content-Type: application/json");
    echo json_encode(["status" => $status, "message" => $message, "data" => $data]);
    exit();
}

// Function to redirect with a status message for non-AJAX requests (e.g., direct form submission).
// This is useful if the frontend doesn't use AJAX for form submission and expects a redirect.
function redirectToBookingPage($status, $message) {
    // Build the URL with status and message parameters.
    // urlencode() makes sure special characters in the message are handled correctly.
    $redirect_url = "../frontend/pages/booking.html?status=" . urlencode($status) . "&message=" . urlencode($message);
    header("Location: " . $redirect_url);
    exit();
}

// Determine if the request is an AJAX request.
// This is a common way to check if the frontend expects a JSON response or a redirect.
$is_ajax = isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";

// Check if an 'action' parameter is provided in the GET request.
// This allows us to handle different requests (like fetching services) with one file.
if (isset($_GET["action"])) {
    $action = $_GET["action"];

    // If the action is 'get_services', we need to fetch the list of services.
    if ($action === "get_services") {
        try {
            // Prepare and execute a query to get all active services.
            $stmt = $pdo->prepare("SELECT service_id, service_name_en, service_name_ar, price, duration_minutes FROM services WHERE is_active = 1 ORDER BY service_id ASC");
            $stmt->execute();
            // Fetch all services as an associative array.
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Send them back as a success JSON response.
            sendJsonResponse("success", "Services fetched successfully.", $services);
        } catch (PDOException $e) {
            // Handle any database errors during service fetching.
            sendJsonResponse("error", "Failed to fetch services: " . $e->getMessage());
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If the request method is POST, it means a booking form was submitted.

    // Get data from the booking form.
    $full_name = $_POST["full_name"] ?? "";
    $email = $_POST["email"] ?? "";
    $phone = $_POST["phone"] ?? "";
    $car_model = $_POST["car_model"] ?? "";
    $service_id = $_POST["service_id"] ?? "";
    $booking_date = $_POST["booking_date"] ?? "";
    $booking_time = $_POST["booking_time"] ?? "";
    $notes = $_POST["notes"] ?? "";

    // Get user_id from session if logged in, otherwise it will be NULL.
    $user_id = $_SESSION["user_id"] ?? null;

    // --- Basic Validation --- //
    // Check if all required fields are filled.
    if (empty($full_name) || empty($email) || empty($phone) || empty($car_model) || empty($service_id) || empty($booking_date) || empty($booking_time)) {
        $message = "Please fill in all required fields for the booking.";
        if ($is_ajax) {
            sendJsonResponse("error", $message);
        } else {
            redirectToBookingPage("error", $message);
        }
    }

    // Validate email format.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        if ($is_ajax) {
            sendJsonResponse("error", $message);
        } else {
            redirectToBookingPage("error", $message);
        }
    }

    // Validate service_id to ensure it's a number.
    if (!is_numeric($service_id)) {
        $message = "Invalid service selected.";
        if ($is_ajax) {
            sendJsonResponse("error", $message);
        } else {
            redirectToBookingPage("error", $message);
        }
    }

    // Validate date and time formats (basic check).
    // PHP's strtotime() can parse many date/time formats. If it returns false, it's invalid.
    if (strtotime($booking_date) === false || strtotime($booking_time) === false) {
        $message = "Invalid date or time format.";
        if ($is_ajax) {
            sendJsonResponse("error", $message);
        } else {
            redirectToBookingPage("error", $message);
        }
    }

    // Optional: Check if the service_id actually exists in the database.
    try {
        $stmt = $pdo->prepare("SELECT service_id FROM services WHERE service_id = :service_id AND is_active = 1");
        $stmt->bindParam(":service_id", $service_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            $message = "Selected service is not available.";
            if ($is_ajax) {
                sendJsonResponse("error", $message);
            } else {
                redirectToBookingPage("error", $message);
            }
        }
    } catch (PDOException $e) {
        $message = "Database error checking service: " . $e->getMessage();
        if ($is_ajax) {
            sendJsonResponse("error", $message);
        } else {
            redirectToBookingPage("error", $message);
        }
    }

    // --- Insert new booking into the database --- //
    try {
        // Prepare an SQL INSERT statement to add the new booking details.
        $stmt = $pdo->prepare(
            "INSERT INTO bookings (user_id, service_id, customer_name, customer_email, customer_phone, car_model, booking_date, booking_time, notes) VALUES (:user_id, :service_id, :customer_name, :customer_email, :customer_phone, :car_model, :booking_date, :booking_time, :notes)"
        );
        // Bind the values from our form and session to the placeholders in the SQL statement.
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT); // user_id can be NULL, so specify type.
        $stmt->bindParam(":service_id", $service_id, PDO::PARAM_INT);
        $stmt->bindParam(":customer_name", $full_name);
        $stmt->bindParam(":customer_email", $email);
        $stmt->bindParam(":customer_phone", $phone);
        $stmt->bindParam(":car_model", $car_model);
        $stmt->bindParam(":booking_date", $booking_date);
        $stmt->bindParam(":booking_time", $booking_time);
        $stmt->bindParam(":notes", $notes);
        // Execute the statement to save the new booking.
        $stmt->execute();

        // If the insertion is successful, send a success response.
        $message = "Booking successful! We look forward to seeing you.";
        if ($is_ajax) {
            sendJsonResponse("success", $message);
        } else {
            redirectToBookingPage("success", $message);
        }

    } catch (PDOException $e) {
        // If there's a database error during insertion, send an error response.
        $message = "Booking failed: " . $e->getMessage();
        if ($is_ajax) {
            sendJsonResponse("error", $message);
        } else {
            redirectToBookingPage("error", $message);
        }
    }

} else {
    // If the request method is not GET with action or POST, it's an invalid access.
    $message = "Invalid request method.";
    if ($is_ajax) {
        sendJsonResponse("error", $message);
    } else {
        redirectToBookingPage("error", $message);
    }
}

?>
