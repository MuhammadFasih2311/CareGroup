<?php
include("../connect.php");

if (!isset($_POST['id'])) {
    echo "ID missing";
    exit;
}

$id = intval($_POST['id']);

$get = mysqli_query($conn, "SELECT image FROM diseases WHERE id='$id'");
$disease = mysqli_fetch_assoc($get);

if ($disease && !empty($disease['image'])) {
    $imgPath = "../" . $disease['image'];
    if (file_exists($imgPath)) {
        unlink($imgPath); 
    }
}

mysqli_query($conn, "DELETE FROM diseases WHERE id='$id'");

echo "success";
exit;
?>
