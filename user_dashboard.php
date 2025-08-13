<?php
// Start session to access user data
session_start();
// Include database configuration
require_once "config.php";

// Check if user is logged in and has 'User' role; redirect to login if not
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] != "User") {
    header("Location: login.html");
    exit;
}

// Fetch user data for profile picture
$citizen_id = $_SESSION["CitizenID"];
// Prepare query to get user's full name and profile picture
$stmt = $pdo->prepare("SELECT FullName, ProfilePicture FROM citizens WHERE CitizenID = ?");
$stmt->execute([$citizen_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect to login with error if user not found
if (!$user) {
    setFlash('error', 'User not found.');
    header("Location: login.html");
    exit;
}

// Fetch count of pending complaints for the user
$stmt = $pdo->prepare("SELECT COUNT(*) FROM complaints WHERE CitizenID = ? AND Status = 'Pending'");
$stmt->execute([$citizen_id]);
$pending_complaints = $stmt->fetchColumn();

// Fetch count of pending emergency requests for the user
$stmt = $pdo->prepare("SELECT COUNT(*) FROM emergencyrequests WHERE CitizenID = ? AND Status = 'Pending'");
$stmt->execute([$citizen_id]);
$pending_emergencies = $stmt->fetchColumn();

// Fetch count of unpaid utility bills for the user
$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilitybills WHERE CitizenID = ? AND Status = 'Unpaid'");
$stmt->execute([$citizen_id]);
$unpaid_bills = $stmt->fetchColumn();

// Fetch count of pending traffic violations for the user
$stmt = $pdo->prepare("SELECT COUNT(*) FROM traffic_violations WHERE CitizenID = ? AND Status = 'Pending'");
$stmt->execute([$citizen_id]);
$pending_violations = $stmt->fetchColumn();

// Fetch count of upcoming events
$stmt = $pdo->prepare("SELECT COUNT(*) FROM events WHERE Status = 'Upcoming'");
$stmt->execute();
$upcoming_events = $stmt->fetchColumn();

// Include the HTML template for the dashboard
include "user_dashboard.html";
?>