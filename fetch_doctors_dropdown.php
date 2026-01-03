<?php
include('connect.php');

$hospital = isset($_GET['hospital']) ? $conn->real_escape_string($_GET['hospital']) : '';
$specialty = isset($_GET['specialty']) ? $conn->real_escape_string($_GET['specialty']) : '';

if ($hospital && $specialty) {
  $result = $conn->query("SELECT id, name FROM doctors WHERE hospital='$hospital' AND specialty='$specialty' ORDER BY name ASC");
  $doctors = [];
  while($row = $result->fetch_assoc()) {
    $doctors[] = $row;
  }
  echo json_encode($doctors);
}
?>
