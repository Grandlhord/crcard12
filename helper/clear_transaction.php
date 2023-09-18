<?php
    // Clearing Transaction view
    session_start();
    unset($_SESSION["account"]);
    unset($_SESSION['account_id']);
    header("Location: ../index.php");
?>