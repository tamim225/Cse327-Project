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

// Fetch complaints based on user role
$citizen_id = $_SESSION["CitizenID"];
if ($_SESSION["Role"] == "Admin") {
    // For admins, fetch all complaints from the database
    $stmt = $pdo->query("SELECT ComplaintID, CitizenID, Subject, Description, Status, DateFiled FROM complaints");
} else {
    // For regular users, fetch only their own complaints
    $stmt = $pdo->prepare("SELECT ComplaintID, CitizenID, Subject, Description, Status, DateFiled FROM complaints WHERE CitizenID = ?");
    $stmt->execute([$citizen_id]);
}
// Store all fetched complaints in an array
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle CRUD actions for complaints
$edit_complaint = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the action type from the form submission
    $action = $_POST["action"];
    
    if ($action == "create" && $_SESSION["Role"] == "User") {
        // Handle complaint creation for regular users
        $subject = $_POST["subject"];
        $description = $_POST["description"];
        // Insert new complaint into the database with current date
        $stmt = $pdo->prepare("INSERT INTO complaints (CitizenID, Subject, Description, DateFiled) VALUES (?, ?, ?, CURDATE())");
        $stmt->execute([$citizen_id, $subject, $description]);
        // Redirect to complaints page after creation
        header("Location: complaints.php");
        exit;
    }
    
    if ($action == "update") {
        // Handle complaint updates
        $id = $_POST["id"];
        $subject = $_POST["subject"];
        $description = $_POST["description"];
        $status = isset($_POST["status"]) ? $_POST["status"] : null;
        
        if ($_SESSION["Role"] == "Admin") {
            // Admins can update subject, description, and status of any complaint
            $stmt = $pdo->prepare("UPDATE complaints SET Subject = ?, Description = ?, Status = ? WHERE ComplaintID = ?");
            $stmt->execute([$subject, $description, $status, $id]);
        } else {
            // Users can only update subject and description of their own pending complaints
            $stmt = $pdo->prepare("UPDATE complaints SET Subject = ?, Description = ? WHERE ComplaintID = ? AND CitizenID = ? AND Status = 'Pending'");
            $stmt->execute([$subject, $description, $id, $citizen_id]);
        }
        // Redirect to complaints page after update
        header("Location: complaints.php");
        exit;
    }
}

// Handle GET requests for editing or deleting complaints
if (isset($_GET["action"]) && isset($_GET["id"])) {
    $id = $_GET["id"];
    if ($_GET["action"] == "edit") {
        // Fetch complaint details for editing
        $stmt = $pdo->prepare("SELECT * FROM complaints WHERE ComplaintID = ?");
        if ($_SESSION["Role"] != "Admin") {
            // For users, only fetch their own pending complaints
            $stmt = $pdo->prepare("SELECT * FROM complaints WHERE ComplaintID = ? AND CitizenID = ? AND Status = 'Pending'");
            $stmt->execute([$id, $citizen_id]);
        } else {
            // For admins, fetch any complaint by ID
            $stmt->execute([$id]);
        }
        // Store complaint data for editing
        $edit_complaint = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    if ($_GET["action"] == "delete") {
        // Handle complaint deletion
        if ($_SESSION["Role"] == "Admin") {
            // Admins can delete any complaint
            $stmt = $pdo->prepare("DELETE FROM complaints WHERE ComplaintID = ?");
            $stmt->execute([$id]);
        } else {
            // Users can only delete their own pending complaints
            $stmt = $pdo->prepare("DELETE FROM complaints WHERE ComplaintID = ? AND CitizenID = ? AND Status = 'Pending'");
            $stmt->execute([$id, $citizen_id]);
        }
        // Redirect to complaints page after deletion
        header("Location: complaints.php");
        exit;
    }
}

// Include the HTML template to display the complaints interface
include "complaints.html";
?>