<?php
error_log("LOGGING OUT");
session_destroy();
header("Location: ../../index.php");
exit;
?>
