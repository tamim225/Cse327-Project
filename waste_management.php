<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["CitizenID"])) {
    header("Location: login.html"); // Send HTTP header to redirect to login page
    exit;                           // Stop script execution after redirect
}

// Fetch waste schedules
$stmt = $pdo->query("SELECT WasteID, Area, Schedule, Status FROM wastemanagement");                  // Run query
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Store all results as an associative array

// Handle CRUD actions (Admin only)
$edit_schedule = null;
if ($_SESSION["Role"] == "Admin") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST["action"];
        
         // If the action is "create", insert a new schedule into the database
        if ($action == "create") {
            $area = $_POST["area"];    // Get area name from form
            $schedule = $_POST["schedule"];   // Get schedule time from form
            $stmt = $pdo->prepare("INSERT INTO wastemanagement (Area, Schedule, Status) VALUES (?, ?, 'Scheduled')");
            $stmt->execute([$area, $schedule]);

             // Redirect back to the waste management page to refresh data
            header("Location: waste_management.php");
            exit;       // Stop script execution
        }

         // If the action is "update", modify an existing schedule
        
        if ($action == "update") {
            $id = $_POST["id"];         // Schedule ID to update
            $area = $_POST["area"];     // Updated area
            $schedule = $_POST["schedule"];    // Updated schedule
            $status = $_POST["status"];        // Updated status
            $stmt = $pdo->prepare("UPDATE wastemanagement SET Area = ?, Schedule = ?, Status = ? WHERE WasteID = ?");
            $stmt->execute([$area, $schedule, $status, $id]);

            // Redirect back to waste management page after update
            header("Location: waste_management.php");
            exit;            // Stop script execution
        }
    }

    // If there is an action and an ID in the GET request (edit/delete actions)

    if (isset($_GET["action"]) && isset($_GET["id"])) {
        $id = $_GET["id"];       // Get the schedule ID from the URL
        if ($_GET["action"] == "edit") {
            $stmt = $pdo->prepare("SELECT * FROM wastemanagement WHERE WasteID = ?");
            $stmt->execute([$id]);          // Execute with the given ID
            $edit_schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
         // If the action is "delete", remove the schedule from the database
        if ($_GET["action"] == "delete") {
            $stmt = $pdo->prepare("DELETE FROM wastemanagement WHERE WasteID = ?");
            $stmt->execute([$id]);        // Execute delete command
            
             // Redirect back to waste management page after deletion
            header("Location: waste_management.php");
            exit;
        }
    }
}

include "waste_management.html";
?>