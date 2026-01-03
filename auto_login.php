<?php
include('connect.php');

if (isset($_SESSION['admin_id']) || isset($_SESSION['doctor_id']) || isset($_SESSION['user_id'])) {
    return;
}

if (isset($_COOKIE['remember_token'])) {

    $token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM doctors WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $doctor = $stmt->get_result()->fetch_assoc();

    if ($doctor) {
        $_SESSION['doctor_id']    = $doctor['id'];
        $_SESSION['doctor_name']  = $doctor['name'];
        $_SESSION['doctor_email'] = $doctor['email'];
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM admin WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin) {
        $_SESSION['admin_id']    = $admin['id'];
        $_SESSION['admin_name']  = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        return;
    }
}
?>
