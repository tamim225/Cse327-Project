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

// Fetch all events from the database
$stmt = $pdo->query("SELECT EventID, Title, Description, Location, Date, Status FROM events");
// Store fetched events in an array
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle CRUD actions, restricted to Admin role
$edit_event = null;
if ($_SESSION["Role"] == "Admin") {
    // Process form submissions for create or update actions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the action type from the form submission
        $action = $_POST["action"];
        
        if ($action == "create") {
            // Handle event creation
            $title = $_POST["title"];
            $description = $_POST["description"];
            $location = $_POST["location"];
            $date = $_POST["date"];
            // Insert new event into the database with default status 'Upcoming'
            $stmt = $pdo->prepare("INSERT INTO events (Title, Description, Location, Date, Status) VALUES (?, ?, ?, ?, 'Upcoming')");
            $stmt->execute([$title, $description, $location, $date]);
            // Redirect to events page after creation
            header("Location: events.php");
            exit;
        }
        
        if ($action == "update") {
            // Handle event updates
            $id = $_POST["id"];
            $title = $_POST["title"];
            $description = $_POST["description"];
            $location = $_POST["location"];
            $date = $_POST["date"];
            $status = $_POST["status"];
            // Update event details in the database
            $stmt = $pdo->prepare("UPDATE events SET Title = ?, Description = ?, Location = ?, Date = ?, Status = ? WHERE EventID = ?");
            $stmt->execute([$title, $description, $location, $date, $status, $id]);
            // Redirect to events page after update
            header("Location: events.php");
            exit;
        }
    }

    // Handle GET requests for editing or deleting events
    if (isset($_GET["action"]) && isset($_GET["id"])) {
        $id = $_GET["id"];
        if ($_GET["action"] == "edit") {
            // Fetch event details for editing
            $stmt = $pdo->prepare("SELECT * FROM events WHERE EventID = ?");
            $stmt->execute([$id]);
            // Store event data for editing
            $edit_event = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if ($_GET["action"] == "delete") {
            // Handle event deletion
            $stmt = $pdo->prepare("DELETE FROM events WHERE EventID = ?");
            $stmt->execute([$id]);
            // Redirect to events page after deletion
            header("Location: events.php");
            exit;
        }
    }
}

// Include the HTML template to display the events interface
include "events.html";
?>