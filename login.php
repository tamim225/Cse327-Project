<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    
    // Check credentials
    $stmt = $pdo->prepare("SELECT CitizenID, FullName, Password, Role FROM citizens WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user["Password"])) {
        // Store temporary user data
        $_SESSION["temp_CitizenID"] = $user["CitizenID"];
        $_SESSION["temp_FullName"] = $user["FullName"];
        $_SESSION["temp_Role"] = $user["Role"];
        
        if ($user["Role"] == "User") {
            // Clear old OTPs for this user
            $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE CitizenID = ?");
            $stmt->execute([$user["CitizenID"]]);
            
            // Generate 6-digit OTP
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            
            // Set correct timestamps
            $created_at = date("Y-m-d H:i:s");
            $expires_at = date("Y-m-d H:i:s", strtotime("+5 minutes"));
            
            // Store OTP
            $stmt = $pdo->prepare("INSERT INTO otp_codes (CitizenID, otp, created_at, expires_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user["CitizenID"], $otp, $created_at, $expires_at]);
            
            // Send OTP via email
            $to = $email;
            $subject = "Smart City Login OTP";
            $message = "Your one-time password is: $otp\nIt is valid for 5 minutes.";
            $headers = "From: no-reply@smartcity.com";
            mail($to, $subject, $message, $headers);
            
            // Redirect to OTP verification
            header("Location: verify_otp.php");
            exit;
        } else {
            // Admins skip 2FA
            session_unset();
            $_SESSION["CitizenID"] = $user["CitizenID"];
            $_SESSION["FullName"] = $user["FullName"];
            $_SESSION["Role"] = $user["Role"];
            header("Location: admin_dashboard.php");
            exit;
        }
    } else {
        header("Location: login.html?error=" . urlencode("Invalid email or password."));
        exit;
    }
} else {
    header("Location: login.html");
    exit;
}
?>