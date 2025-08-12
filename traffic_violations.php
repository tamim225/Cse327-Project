<?php
session_start();    //session start
require_once "config.php";  //loads config.php file

if (!isset($_SESSION["CitizenID"])) {   //if no logged-in user, redirect them to the login page
    header("Location: ../html/login.html");
    exit;
}

// Fetch violations
$citizen_id = $_SESSION["CitizenID"];   //stores logged-in user's ID in variable
if ($_SESSION["Role"] == "Admin") {     //if admin
    $stmt = $pdo->query("SELECT ViolationID, CitizenID, Description, FineAmount, DateOccurred, Status FROM traffic_violations");
} else {                                //if user
    $stmt = $pdo->prepare("SELECT ViolationID, CitizenID, Description, FineAmount, DateOccurred, Status FROM traffic_violations WHERE CitizenID = ?");
    $stmt->execute([$citizen_id]);
}
$violations = $stmt->fetchAll(PDO::FETCH_ASSOC);//creates array of keys

// Create and Update Functions
$edit_violation = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "create" && $_SESSION["Role"] == "Admin") {//create(admin)
        $citizen_id = $_POST["citizen_id"];
        $description = $_POST["description"];
        $fine_amount = $_POST["fine_amount"];
        $date_occurred = $_POST["date_occurred"];
        $stmt = $pdo->prepare("INSERT INTO traffic_violations (CitizenID, Description, FineAmount, DateOccurred, Status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$citizen_id, $description, $fine_amount, $date_occurred]);
        header("Location: traffic_violations.php");
        exit;
    }

    if ($action == "update" && $_SESSION["Role"] == "Admin") {//update(admin)
        $id = $_POST["id"];
        $citizen_id = $_POST["citizen_id"];
        $description = $_POST["description"];
        $fine_amount = $_POST["fine_amount"];
        $date_occurred = $_POST["date_occurred"];
        $status = $_POST["status"];
        $stmt = $pdo->prepare("UPDATE traffic_violations SET CitizenID = ?, Description = ?, FineAmount = ?, DateOccurred = ?, Status = ? WHERE ViolationID = ?");
        $stmt->execute([$citizen_id, $description, $fine_amount, $date_occurred, $status, $id]);
        header("Location: traffic_violations.php");
        exit;
    }
}

if (isset($_GET["action"]) && isset($_GET["id"])) {// check if there is an action and id in the URL
    $id = $_GET["id"];                             // stores violation ID in ID
    if ($_GET["action"] == "edit" && $_SESSION["Role"] == "Admin") {// admin wants to edit a violation
        $stmt = $pdo->prepare("SELECT * FROM traffic_violations WHERE ViolationID = ?");
        $stmt->execute([$id]);
        $edit_violation = $stmt->fetch(PDO::FETCH_ASSOC);//fetches full data
    }

    if ($_GET["action"] == "pay" && $_SESSION["Role"] == "User") {// pay(user)
        $stmt = $pdo->prepare("UPDATE traffic_violations SET Status = 'Paid' WHERE ViolationID = ? AND CitizenID = ? AND Status = 'Pending'");
        $stmt->execute([$id, $citizen_id]);
        header("Location: traffic_violations.php");
        exit;
    }

    if ($_GET["action"] == "delete" && $_SESSION["Role"] == "Admin") {// delete(admin)
        $stmt = $pdo->prepare("DELETE FROM traffic_violations WHERE ViolationID = ?");
        $stmt->execute([$id]);
        header("Location: traffic_violations.php");
        exit;
    }
}

include "traffic_violations.html";// display the violations list and forms
?>