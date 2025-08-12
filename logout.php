<?php
// Start session to access session data
session_start();
// Clear all session variables
session_unset();
// Destroy the session
session_destroy();
// Redirect to login page after logout
header("Location: login.html");
exit;
?>