<?php
include("../connect.php");

if (!isset($_POST['id'])) {
    echo "ID missing";
    exit;
}

$id = intval($_POST['id']);

$get = mysqli_query($conn, "SELECT image FROM doctors WHERE id='$id'");
$doc = mysqli_fetch_assoc($get);

if ($doc && !empty($doc['image'])) {
    $imgPath = "../" . $doc['image'];
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}

mysqli_query($conn, "DELETE FROM doctors WHERE id='$id'");

echo "success";
exit;
?>
