
<?php
// This file is responsible for connecting our PHP application to the MySQL database.
// We use PDO (PHP Data Objects) for a more secure and flexible database interaction.

// 1. Define Database Connection Parameters
// These are the details needed to tell PHP how to find and log into your database.
// IMPORTANT: In a real-world application, these credentials should be stored outside
// of your web-accessible directory and ideally in environment variables for security.

$host = 'localhost'; // The database server address. 'localhost' usually means it's on the same machine.
$dbname = 'shinehub_db'; // The name of the database we created in database.sql.
$username = 'root'; // Your MySQL username. For XAMPP, 'root' is the default.
$password = ''; // Your MySQL password. For XAMPP, the default is usually empty.

// 2. Construct the DSN (Data Source Name)
// The DSN is a string that contains the information required to connect to the database.
// It specifies the database type (mysql), host, and database name.
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// 3. Set PDO Options
// These options configure how PDO behaves. They are important for security and error handling.
$options = [
    // PDO::ATTR_ERRMODE: This tells PDO how to handle errors.
    // PDO::ERRMODE_EXCEPTION: This means PDO will throw exceptions on errors, which is good
    // for debugging and handling errors gracefully in your code.
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // PDO::ATTR_DEFAULT_FETCH_MODE: This sets the default way PDO fetches data.
    // PDO::FETCH_ASSOC: This means data will be returned as an associative array (column_name => value).
    // This is often easier to work with than numeric arrays.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

    // PDO::ATTR_EMULATE_PREPARES: This can be set to false for better performance and security
    // when using prepared statements, especially with older MySQL versions. It ensures that
    // the database itself prepares the statement, not PDO.
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 4. Establish the Database Connection using a try-catch block
// A try-catch block is used here to handle potential errors during the connection process.
// If the connection fails (e.g., wrong credentials, database not found), it will 
    // 
    // catch the error and display a message.
try {
    // Create a new PDO instance, passing in the DSN, username, password, and options.
    $pdo = new PDO($dsn, $username, $password, $options);
    // If the connection is successful, $pdo will be our database connection object.
} catch (PDOException $e) {
    // If an error occurs, this block will execute.
    // die(): This function prints a message and exits the current script.
    // getMessage(): This method of the PDOException object returns a string describing the error.
    die("Connection failed: " . $e->getMessage());
}

// Now, $pdo can be used in other PHP files to interact with the database.
// Example usage in another file:
// require_once 'connect.php'; // Include this connection file
// $stmt = $pdo->query('SELECT * FROM users');
// while ($row = $stmt->fetch()) {
//     echo $row['full_name'];
// }
?>
