<?php
// Check if the admin is logged in
if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
?>

