<?php
include("../connect.php");

$id = intval($_POST['id']);

$q = mysqli_query($conn, "SELECT * FROM doctors WHERE id = $id");
$d = mysqli_fetch_assoc($q);

if(!$d){
    echo "error";
    exit;
}

$oldImg = $d['image'];

$newImg = "";

if($oldImg != "" && file_exists("../" . $oldImg)) {

    $ext = pathinfo($oldImg, PATHINFO_EXTENSION);

    $newImg = "images/doctors/" . time() . "_copy_" . rand(1000,9999) . "." . $ext;

    copy("../" . $oldImg, "../" . $newImg);
}

mysqli_query($conn, "
    INSERT INTO doctors (name, specialty, hospital, diseases, description, image, created_at)
    VALUES (
        '".mysqli_real_escape_string($conn,$d['name'])."',
        '".mysqli_real_escape_string($conn,$d['specialty'])."',
        '".mysqli_real_escape_string($conn,$d['hospital'])."',
        '".mysqli_real_escape_string($conn,$d['diseases'])."',
        '".mysqli_real_escape_string($conn,$d['description'])."',
        '$newImg',
        NOW()
    )
");

echo "success";
?>
