<?php

include("../connect.php");

$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<p class='text-muted'>Invalid id.</p>";
    exit;
}

if ($type === 'doctor') {
    $res = mysqli_query($conn, "SELECT * FROM doctors WHERE id = $id LIMIT 1");
    if ($r = mysqli_fetch_assoc($res)) {
        echo "<div class='text-center'>";
        echo "<img src='../".htmlspecialchars($r['image'])."' alt='Doctor' style='width:90px;height:90px;border-radius:8px;object-fit:cover;margin-bottom:12px;'>";
        echo "<h5>".htmlspecialchars($r['name'])."</h5>";
        echo "<p class='text-muted'>".htmlspecialchars($r['specialty'])." — ".htmlspecialchars($r['hospital'])."</p>";
        echo "<p>".nl2br(htmlspecialchars($r['description']))."</p>";
        echo "</div>";
    } else {
        echo "<p class='text-muted'>Doctor not found.</p>";
    }
} else {
    $res = mysqli_query($conn, "SELECT id,name,email,phone,created_at FROM users WHERE id = $id LIMIT 1");
    if ($r = mysqli_fetch_assoc($res)) {
        echo "<div class='text-center'>";
        echo "<h5>".htmlspecialchars($r['name'])."</h5>";
        echo "<p class='text-muted'>Email: ".htmlspecialchars($r['email'])."</p>";
        echo "<p class='text-muted'>Phone: ".htmlspecialchars($r['phone'])."</p>";
        echo "<p class='small text-muted'>Member since: ".htmlspecialchars($r['created_at'])."</p>";
        echo "</div>";
    } else {
        echo "<p class='text-muted'>User not found.</p>";
    }
}
