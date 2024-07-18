<?php
session_start(); // Make sure to start the session

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Log the logout action for debugging
error_log("LOGGING OUT");

// Redirect to the home page
header("Location: ../../index.php");
exit;
?>
