<?php
// Check if session is not started and initiate it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Attempt to connect to the MySQL database using PDO
try {
    // Initialize PDO with database credentials
    $pdo = new PDO("mysql:host=localhost;dbname=smartcitydb", "root", "");
    // Enable exception-based error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Terminate script and display connection error
    die("Connection failed: " . $e->getMessage());
}

// Function to set a one-time flash message in session
function setFlash($type, $message) {
    // Store message type and content in session
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Function to retrieve and clear flash message
function getFlash() {
    // Check if flash message exists
    if (isset($_SESSION['flash'])) {
        // Store flash message for return
        $flash = $_SESSION['flash'];
        // Remove flash message from session
        unset($_SESSION['flash']);
        return $flash;
    }
    // Return null if no flash message
    return null;
}
?>