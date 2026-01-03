<?php
include('connect.php');
session_start();

if (!isset($_SESSION['user_id'])) exit('Unauthorized');

$id = $_POST['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE appointments SET status='canceled' WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

echo $stmt->affected_rows ? "success" : "error";
?>
