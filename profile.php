<?php
// Start the session to access user data
session_start();
// Include the database configuration file
require_once "config.php";

// Check if the user is logged in; if not, redirect to login page
if (!isset($_SESSION["CitizenID"])) {
    header("Location: login.html");
    exit;
}

// Retrieve user ID and role from session
$citizen_id = $_SESSION["CitizenID"];
$role = $_SESSION["Role"];
// Set dashboard URL based on user role (Admin or User)
$dashboard_url = ($role == "Admin") ? "admin_dashboard.php" : "user_dashboard.php";

// Fetch user data from the database
$stmt = $pdo->prepare("SELECT FullName, Email, Address, Phone, ProfilePicture FROM citizens WHERE CitizenID = ?");
$stmt->execute([$citizen_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user data exists; if not, set error message and redirect to login
if (!$user) {
    setFlash('error', 'User not found.');
    header("Location: login.html");
    exit;
}

// Handle form submission for profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $full_name = trim($_POST["full_name"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);
    // Hash password if provided; otherwise, keep existing password
    $password = !empty($_POST["password"]) ? password_hash(trim($_POST["password"]), PASSWORD_DEFAULT) : null;

    // Handle profile picture upload
    $profile_picture = $user["ProfilePicture"];
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        // Define allowed file types and maximum file size (2MB)
        $allowed_types = ["image/jpeg", "image/png", "image/gif"];
        $max_size = 2 * 1024 * 1024; // 2MB
        $file_type = $_FILES["profile_picture"]["type"];
        $file_size = $_FILES["profile_picture"]["size"];
        $file_tmp = $_FILES["profile_picture"]["tmp_name"];

        // Validate file type and size
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            // Generate unique file name using citizen ID and file extension
            $ext = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
            $new_file_name = "user_" . $citizen_id . "." . $ext;
            $upload_dir = "uploads/";
            
            // Create uploads directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $upload_path = $upload_dir . $new_file_name;

            // Delete old profile picture if it exists
            if ($profile_picture && file_exists($profile_picture)) {
                unlink($profile_picture);
            }

            // Move uploaded file to the upload directory
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $profile_picture = $upload_path;
            } else {
                // Set error message if upload fails
                setFlash('error', 'Failed to upload profile picture.');
            }
        } else {
            // Set error message for invalid file type or size
            setFlash('error', 'Invalid file type or size. Only JPG, PNG, GIF files under 2MB are allowed.');
        }
    }

    // Update user data in the database
    $sql = "UPDATE citizens SET FullName = ?, Address = ?, Phone = ?, ProfilePicture = ?";
    $params = [$full_name, $address, $phone, $profile_picture];
    
    // Include password in update if provided
    if ($password) {
        $sql .= ", Password = ?";
        $params[] = $password;
    }
    
    $sql .= " WHERE CitizenID = ?";
    $params[] = $citizen_id;

    // Execute the update query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        // Set success message and redirect on successful update
        setFlash('success', 'Profile updated successfully.');
        header("Location: profile.php");
        exit;
    } else {
        // Set error message if update fails
        setFlash('error', 'Failed to update profile.');
    }
}

// Include the HTML template to display the profile interface
include "profile.html";
?>