<?php
include("../connect.php");

if (!isset($_POST['id'])) {
    echo "ID missing";
    exit;
}

$id = intval($_POST['id']);

error_log("Deleting user with ID: $id"); 

$query = "DELETE FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "success";
} else {
    echo "error: " . mysqli_error($conn);
}
exit;
?>
