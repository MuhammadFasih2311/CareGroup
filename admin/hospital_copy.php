<?php
include("../connect.php");

if (!isset($_POST['id'])) {
    echo "ID missing";
    exit;
}

$id = intval($_POST['id']);

$q = mysqli_query($conn, "SELECT * FROM hospitals WHERE id = $id");
$hospital = mysqli_fetch_assoc($q);

if(!$hospital){
    echo "error";
    exit;
}

$oldImg = $hospital['image']; 
$newImg = "";

if ($oldImg != "" && file_exists("../" . $oldImg)) {

    $ext = pathinfo($oldImg, PATHINFO_EXTENSION);

    $newImg = "images/hospitals/" . time() . "_copy_" . rand(1000,9999) . "." . $ext;

    copy("../" . $oldImg, "../" . $newImg);
}

mysqli_query($conn, "
    INSERT INTO hospitals (name, address, city, image, created_at)
    VALUES (
        '".mysqli_real_escape_string($conn, $hospital['name'])."',
        '".mysqli_real_escape_string($conn, $hospital['address'])."',
        '".mysqli_real_escape_string($conn, $hospital['city'])."',
        '$newImg',
        NOW()
    )
");

echo "success";
?>
