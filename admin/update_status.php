<?php
include("../connect.php");

$id = intval($_POST['id']);
$status = mysqli_real_escape_string($conn, $_POST['status']);

$update = mysqli_query($conn, "UPDATE appointments SET status='$status' WHERE id=$id");

echo json_encode([
    "success" => $update ? true : false
]);
