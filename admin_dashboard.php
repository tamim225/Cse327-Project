<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["Role"]) || $_SESSION["Role"] != "Admin") {
    header("Location: login.html");
    exit;
}

// Fetch user data for profile picture
$citizen_id = $_SESSION["CitizenID"];
$stmt = $pdo->prepare("SELECT FullName, ProfilePicture FROM citizens WHERE CitizenID = ?");
$stmt->execute([$citizen_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    setFlash('error', 'User not found.');
    header("Location: login.html");
    exit;
}

// Fetch counts for notification badges
$stmt = $pdo->prepare("SELECT COUNT(*) FROM complaints WHERE Status = 'Pending'");
$stmt->execute();
$pending_complaints = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM emergencyrequests WHERE Status = 'Pending'");
$stmt->execute();
$pending_emergencies = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM utilitybills WHERE Status = 'Unpaid'");
$stmt->execute();
$unpaid_bills = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM traffic_violations WHERE Status = 'Pending'");
$stmt->execute();
$pending_violations = $stmt->fetchColumn();

include "admin_dashboard.html";
