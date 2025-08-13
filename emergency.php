<?php
// Start session to manage user authentication and role checks
session_start();

// Include database configuration
require_once "config.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION["CitizenID"])) {
    header("Location: login.html");
    exit;
}

// FETCH EMERGENCY REQUESTS 
// Get logged-in citizen ID
$citizen_id = $_SESSION["CitizenID"];

// Admins see all requests, regular users see only their own
if ($_SESSION["Role"] == "Admin") {
    $stmt = $pdo->query(
        "SELECT RequestID, CitizenID, Type, Description, Status, DateRequested FROM emergencyrequests"
    );
} else {
    $stmt = $pdo->prepare(
        "SELECT RequestID, CitizenID, Type, Description, Status, DateRequested 
         FROM emergencyrequests 
         WHERE CitizenID = ?"
    );
    $stmt->execute([$citizen_id]);
}

// Store fetched requests for display in HTML
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Variable to store a single request when editing
$edit_request = null;

// HANDLE CREATE / UPDATE ACTIONS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    
    // CREATE new emergency request (Users only)
    if ($action == "create" && $_SESSION["Role"] == "User") {
        $type = $_POST["type"];
        $description = $_POST["description"];
        
        $stmt = $pdo->prepare(
            "INSERT INTO emergencyrequests (CitizenID, Type, Description, DateRequested) 
             VALUES (?, ?, ?, CURDATE())"
        );
        $stmt->execute([$citizen_id, $type, $description]);
        
        // Refresh page to show new request
        header("Location: emergency.php");
        exit;
    }
    
    // UPDATE existing emergency request
    if ($action == "update") {
        $id = $_POST["id"];
        $type = $_POST["type"];
        $description = $_POST["description"];
        $status = isset($_POST["status"]) ? $_POST["status"] : null;
        
        if ($_SESSION["Role"] == "Admin") {
            // Admins can update type, description, and status
            $stmt = $pdo->prepare(
                "UPDATE emergencyrequests SET Type = ?, Description = ?, Status = ? WHERE RequestID = ?"
            );
            $stmt->execute([$type, $description, $status, $id]);
        } else {
            // Users can only update their own pending requests (no status change)
            $stmt = $pdo->prepare(
                "UPDATE emergencyrequests SET Type = ?, Description = ? 
                 WHERE RequestID = ? AND CitizenID = ? AND Status = 'Pending'"
            );
            $stmt->execute([$type, $description, $id, $citizen_id]);
        }
        
        // Refresh page to show updated request
        header("Location: emergency.php");
        exit;
    }
}

// HANDLE EDIT / DELETE ACTIONS VIA GET
if (isset($_GET["action"]) && isset($_GET["id"])) {
    $id = $_GET["id"];
    
    // EDIT request: fetch data to pre-fill form
    if ($_GET["action"] == "edit") {
        if ($_SESSION["Role"] != "Admin") {
            // Users can only edit their own pending requests
            $stmt = $pdo->prepare(
                "SELECT * FROM emergencyrequests 
                 WHERE RequestID = ? AND CitizenID = ? AND Status = 'Pending'"
            );
            $stmt->execute([$id, $citizen_id]);
        } else {
            // Admins can edit any request
            $stmt = $pdo->prepare("SELECT * FROM emergencyrequests WHERE RequestID = ?");
            $stmt->execute([$id]);
        }
        $edit_request = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // DELETE request
    if ($_GET["action"] == "delete") {
        if ($_SESSION["Role"] == "Admin") {
            // Admins can delete any request
            $stmt = $pdo->prepare("DELETE FROM emergencyrequests WHERE RequestID = ?");
            $stmt->execute([$id]);
        } else {
            // Users can delete only their own pending requests
            $stmt = $pdo->prepare(
                "DELETE FROM emergencyrequests 
                 WHERE RequestID = ? AND CitizenID = ? AND Status = 'Pending'"
            );
            $stmt->execute([$id, $citizen_id]);
        }
        
        // Refresh page after deletion
        header("Location: emergency.php");
        exit;
    }
}

// LOAD THE EMERGENCY PAGE HTML
include "emergency.html";
?>
