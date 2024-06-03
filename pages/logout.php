<?php
session_start(); // Start the session


session_destroy();


header("Location: ../index.php");
exit;
?>