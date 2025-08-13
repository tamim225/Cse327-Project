<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["CitizenID"])) {
    header("Location: login.html");          // Redirect to login
    exit;                                   // Stop executing further code    
}

// Fetch bills
$citizen_id = $_SESSION["CitizenID"];
if ($_SESSION["Role"] == "Admin") {      // Admin can see all bills from all citizens
    $stmt = $pdo->query("SELECT BillID, CitizenID, BillType, Amount, DueDate, Status FROM utilitybills");
} else {                       // Normal user can only see their own bills
    $stmt = $pdo->prepare("SELECT BillID, CitizenID, BillType, Amount, DueDate, Status FROM utilitybills WHERE CitizenID = ?");
    $stmt->execute([$citizen_id]);
}
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle CRUD actions
$edit_bill = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    
                   // CREATE a new bill (only allowed for Admin)
    if ($action == "create" && $_SESSION["Role"] == "Admin") {

                   // Get form data
        $citizen_id = $_POST["citizen_id"];
        $bill_type = $_POST["bill_type"];
        $amount = $_POST["amount"];
        $due_date = $_POST["due_date"];
        $stmt = $pdo->prepare("INSERT INTO utilitybills (CitizenID, BillType, Amount, DueDate, Status) VALUES (?, ?, ?, ?, 'Unpaid')");
        $stmt->execute([$citizen_id, $bill_type, $amount, $due_date]);
        // Refresh page to show updated list
        header("Location: utility_bills.php");
        exit;
    }


                    // UPDATE an existing bill (only allowed for Admin)
    if ($action == "update" && $_SESSION["Role"] == "Admin") {
        $id = $_POST["id"];
        $citizen_id = $_POST["citizen_id"];
        $bill_type = $_POST["bill_type"];
        $amount = $_POST["amount"];
        $due_date = $_POST["due_date"];
        $status = $_POST["status"];    // Update the bill in database
        $stmt = $pdo->prepare("UPDATE utilitybills SET CitizenID = ?, BillType = ?, Amount = ?, DueDate = ?, Status = ? WHERE BillID = ?");
        $stmt->execute([$citizen_id, $bill_type, $amount, $due_date, $status, $id]);
        header("Location: utility_bills.php");
        exit;
    }
}

if (isset($_GET["action"]) && isset($_GET["id"])) {
    $id = $_GET["id"];       // Get bill ID from URL
    if ($_GET["action"] == "edit" && $_SESSION["Role"] == "Admin") {
        $stmt = $pdo->prepare("SELECT * FROM utilitybills WHERE BillID = ?");
        $stmt->execute([$id]);
        $edit_bill = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // If GET parameters are set, handle Edit/Pay/Delete actions
    
    if ($_GET["action"] == "pay" && $_SESSION["Role"] == "User") {
        $stmt = $pdo->prepare("UPDATE utilitybills SET Status = 'Paid' WHERE BillID = ? AND CitizenID = ? AND Status = 'Unpaid'");
        $stmt->execute([$id, $citizen_id]);
        header("Location: utility_bills.php");
        exit;
    }
    
    if ($_GET["action"] == "delete" && $_SESSION["Role"] == "Admin") {
        $stmt = $pdo->prepare("DELETE FROM utilitybills WHERE BillID = ?");
        $stmt->execute([$id]);
        header("Location: utility_bills.php");
        exit;
    }
}

include "utility_bills.html";
?>