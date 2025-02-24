<?php
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the main index page
header('Location: ../index.php');
exit();
?>
