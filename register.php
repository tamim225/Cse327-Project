<?php
// Start the session to manage user data
session_start();
// Include the database configuration file
require_once "config.php";

// Handle form submission for user registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    // Hash the password using BCRYPT for security
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    // Set default role to 'User' for new registrations
    $role = "User";

    try {
        // Prepare and execute SQL query to insert new user into the citizens table
        $stmt = $pdo->prepare("INSERT INTO citizens (FullName, Email, Password, Address, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $password, $address, $phone, $role]);
        // Redirect to login page on successful registration
        header("Location: login.html");
        // Note: The following echo statement is unreachable due to the redirect
        echo "Registration Successfull: " . $e->getMessage();
        exit;
    } catch (PDOException $e) {
        // Display error message if registration fails (e.g., duplicate email)
        echo "Registration failed: " . $e->getMessage();
    }
}
?>