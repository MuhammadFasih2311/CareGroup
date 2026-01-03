<?php
include("../connect.php");

if (!isset($_POST['id'])) {
    echo "ID missing";
    exit;
}

$id = intval($_POST['id']);

$get = mysqli_query($conn, "SELECT image FROM hospitals WHERE id='$id'");
$hospital = mysqli_fetch_assoc($get);

if ($hospital && !empty($hospital['image'])) {
    $imgPath = "../" . $hospital['image'];
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}

mysqli_query($conn, "DELETE FROM hospitals WHERE id='$id'");

echo "success";
exit;
?>
