<?php
// Start the session to manage user login state
session_start();

// Include database configuration file
require_once "config.php";

// Redirect user to login page if not logged in
if (!isset($_SESSION["CitizenID"])) {
    header("Location: login.html");
    exit;
}

// FETCH EXISTING TRANSPORT ROUTES
$stmt = $pdo->query("SELECT TransportID, RouteName, VehicleType, Timing, Status FROM transport");
$routes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Variable to hold a route's details when editing
$edit_route = null;

// ADMIN-ONLY ACTIONS (CREATE, UPDATE, DELETE)
if ($_SESSION["Role"] == "Admin") {
    
    // Handle form submissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST["action"];
        
        // CREATE new route
        if ($action == "create") {
            $route_name = $_POST["route_name"];
            $vehicle_type = $_POST["vehicle_type"];
            $timing = $_POST["timing"];
            $status = $_POST["status"];
            
            $stmt = $pdo->prepare(
                "INSERT INTO transport (RouteName, VehicleType, Timing, Status) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$route_name, $vehicle_type, $timing, $status]);
            
            // Redirect to refresh page
            header("Location: transport.php");
            exit;
        }
        
        // UPDATE existing route
        if ($action == "update") {
            $id = $_POST["id"];
            $route_name = $_POST["route_name"];
            $vehicle_type = $_POST["vehicle_type"];
            $timing = $_POST["timing"];
            $status = $_POST["status"];
            
            $stmt = $pdo->prepare(
                "UPDATE transport SET RouteName = ?, VehicleType = ?, Timing = ?, Status = ? WHERE TransportID = ?"
            );
            $stmt->execute([$route_name, $vehicle_type, $timing, $status, $id]);
            
            // Redirect after update
            header("Location: transport.php");
            exit;
        }
    }

    // Handle GET requests for editing or deleting routes
    if (isset($_GET["action"]) && isset($_GET["id"])) {
        $id = $_GET["id"];
        
        // EDIT route — fetch data for pre-filling form
        if ($_GET["action"] == "edit") {
            $stmt = $pdo->prepare("SELECT * FROM transport WHERE TransportID = ?");
            $stmt->execute([$id]);
            $edit_route = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // DELETE route
        if ($_GET["action"] == "delete") {
            $stmt = $pdo->prepare("DELETE FROM transport WHERE TransportID = ?");
            $stmt->execute([$id]);
            
            // Redirect after deletion
            header("Location: transport.php");
            exit;
        }
    }
}

// LOAD THE HTML PAGE
include "transport.html";
?>
