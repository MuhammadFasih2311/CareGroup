<?php
include("../connect.php");

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = mysqli_query($conn, "UPDATE appointments SET status='$status' WHERE id=$id");

    echo json_encode([
        "success" => $update ? true : false
    ]);
} else {
    echo json_encode([
        "success" => false
    ]);
}
?>
