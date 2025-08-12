<?php
session_start(); // session start
require_once "config.php"; // load config.php file

if (!isset($_SESSION["CitizenID"])) { // if no one is logged in, then redirect to login
    header("Location: login.html");
    exit;
}

// Fetch institutes
$stmt = $pdo->query("SELECT InstituteID, Name, Address, Type, Status FROM educational_institutes");
$institutes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// create and update functions (Admin only)
$edit_institute = null;
if ($_SESSION["Role"] == "Admin") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST["action"];

        if ($action == "create") {  // create new institute
            $name = $_POST["name"];
            $address = $_POST["address"];
            $type = $_POST["type"];
            $stmt = $pdo->prepare("INSERT INTO educational_institutes (Name, Address, Type, Status) VALUES (?, ?, ?, 'Active')");
            $stmt->execute([$name, $address, $type]);
            header("Location: educational_institutes.php");
            exit;
        }

        if ($action == "update") {  // update an existing institute
            $id = $_POST["id"];
            $name = $_POST["name"];
            $address = $_POST["address"];
            $type = $_POST["type"];
            $status = $_POST["status"];
            $stmt = $pdo->prepare("UPDATE educational_institutes SET Name = ?, Address = ?, Type = ?, Status = ? WHERE InstituteID = ?");
            $stmt->execute([$name, $address, $type, $status, $id]);
            header("Location: educational_institutes.php");
            exit;
        }
    }

    if (isset($_GET["action"]) && isset($_GET["id"])) { // check if action and id are in the URL
        $id = $_GET["id"];
        if ($_GET["action"] == "edit") {    // edit institute 
            $stmt = $pdo->prepare("SELECT * FROM educational_institutes WHERE InstituteID = ?");
            $stmt->execute([$id]);
            $edit_institute = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($_GET["action"] == "delete") {  // delete institute
            $stmt = $pdo->prepare("DELETE FROM educational_institutes WHERE InstituteID = ?");
            $stmt->execute([$id]);
            header("Location: educational_institutes.php");
            exit;
        }
    }
}

include "educational_institutes.html";  // display data
?>