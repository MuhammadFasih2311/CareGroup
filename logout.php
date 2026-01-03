<?php
session_start();
include('connect.php');
if (isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];

    $conn->query("UPDATE users SET remember_token=NULL WHERE remember_token='$token'");
    $conn->query("UPDATE doctors SET remember_token=NULL WHERE remember_token='$token'");
    $conn->query("UPDATE admin SET remember_token=NULL WHERE remember_token='$token'");

    setcookie("remember_token", "", time() - 3600, "/");
}

session_unset();
session_destroy();

header("Location: login.php?logout=true");
exit();
?>
