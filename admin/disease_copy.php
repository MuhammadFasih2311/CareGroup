<?php
include("../connect.php");

$id = intval($_POST['id']);

$q = mysqli_query($conn, "SELECT * FROM diseases WHERE id = $id");
$disease = mysqli_fetch_assoc($q);

if (!$disease) {
    echo "error";
    exit;
}

$oldImg = $disease['image'];

$newImg = "";

if ($oldImg != "" && file_exists("../" . $oldImg)) {

    $ext = pathinfo($oldImg, PATHINFO_EXTENSION);

    $newImg = "images/diseases/" . time() . "_copy_" . rand(1000, 9999) . "." . $ext;

    copy("../" . $oldImg, "../" . $newImg);
}

mysqli_query($conn, "
    INSERT INTO diseases (name, category, description, image, created_at)
    VALUES (
        '" . mysqli_real_escape_string($conn, $disease['name']) . "',
        '" . mysqli_real_escape_string($conn, $disease['category']) . "',
        '" . mysqli_real_escape_string($conn, $disease['description']) . "',
        '$newImg',
        NOW()
    )
");

echo "success";
?>
